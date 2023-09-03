<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
        <img :src="`/storage/${state}`" class="w-100" alt="Image" />
    </div>
</x-dynamic-component>