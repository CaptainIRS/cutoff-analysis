import {
    Livewire,
    Alpine,
} from "../../vendor/livewire/livewire/dist/livewire.esm";

import SelectFormComponentAlpinePlugin from "../../vendor/filament/forms/resources/js/components/select";

Alpine.plugin(SelectFormComponentAlpinePlugin);
// Alpine.plugin(TextInputFormComponentAlpinePlugin);

Livewire.start();

Livewire.on("titleUpdated", (title) => {
    if (title) {
        document.title = title;
    }
});
