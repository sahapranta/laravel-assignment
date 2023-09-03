<?php

namespace App\Filament\Resources\ImageResource\Pages;

use Filament\Actions;
use Filament\Facades\Filament;
use App\Filament\Resources\ImageResource;
use Filament\Resources\Pages\ManageRecords;

class ManageImages extends ManageRecords
{
    protected static string $resource = ImageResource::class;

    protected function getHeaderActions(): array
    {
        Filament::registerRenderHook(
            'panels::body.start',
            fn () => "<style>
            .shim-blue {
                position: relative;
                overflow: hidden;
                background-color: rgba(0, 155, 255, 0.7);
            }

            .shim-blue::after {
                position: absolute;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                transform: translateX(-100%);
                background-image: linear-gradient(90deg,
                        rgba(233, 233, 233, 1) 0,
                        rgba(233, 233, 233, 0.9) 50%,
                        rgba(233, 233, 233, 0.8) 100%);
                animation: shimmer 3.5s ease-out infinite;
                content: '';
            }

            @keyframes shimmer {
                100% {
                    transform: translateX(0%);
                    opacity: 0;
                }
            }
            </style>",
        );

        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-photo')
                ->label('Generate Image')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['user_id'] = auth()->id();
                    return $data;
                }),
        ];
    }
}
