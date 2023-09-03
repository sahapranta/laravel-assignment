<div class="relative bg-gray-400 rounded" style="width: 75%;"
@unless ($getState()>= 100)
    wire:poll.5s
@endunless
>
    <div style="width: <?= $getState() ?>%; height:0.5rem;" class="absolute top-0 rounded shim-blue"></div>
</div>