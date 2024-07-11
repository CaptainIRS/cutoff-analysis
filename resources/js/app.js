import {
    Livewire,
    // Alpine,
} from "../../vendor/livewire/livewire/dist/livewire.esm";

// import SelectFormComponentAlpinePlugin from "../../vendor/filament/forms/resources/js/components/select";
// import TextInputFormComponentAlpinePlugin from "../../vendor/filament/forms/resources/js/components/text-input";

// Alpine.plugin(SelectFormComponentAlpinePlugin);
// Alpine.plugin(TextInputFormComponentAlpinePlugin);

Livewire.start();

Livewire.on("titleUpdated", (data) => {
    if (data && data.title) {
        document.title = data.title;
    }
});
