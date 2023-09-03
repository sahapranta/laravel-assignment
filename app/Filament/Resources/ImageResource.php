<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Image;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\ImageResource\Pages;

class ImageResource extends Resource
{
    protected static ?string $model = Image::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('keywords')
                    ->required()
                    ->hint('Enter specific keywords for Image we will create prompt for you.')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('prompt')
                    ->columnSpanFull()
                    ->hiddenOn('create'),
                \App\Filament\Forms\Components\DisplayImage::make('path')
                    ->hiddenOn('create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('keywords')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                    ->icon(fn (string $state): string => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'working' => 'heroicon-o-arrow-path',
                        'done' => 'heroicon-o-check-circle',
                        'failed' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-exclamation-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'working' => 'primary',
                        'done' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                \App\Filament\Tables\Columns\ProgressBar::make('progress'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Download')
                    ->color('warning')
                    ->url(fn (Image $record): string => $record->path ? asset("storage/$record->path") : '#')
                    ->hidden(fn ($record): bool => is_null($record->path))
                    ->extraAttributes(fn (Image $record): array => ['download' => "{$record->keywords}.png"])
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-arrow-down-on-square'),
                Tables\Actions\EditAction::make()->label('Show'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-photo')
                    ->label('Generate Image')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                        return $data;
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageImages::route('/'),
        ];
    }
}
