import Alpine from "alpinejs";
import Focus from "@alpinejs/focus";

import SelectFormComponentAlpinePlugin from "../../vendor/filament/forms/resources/js/components/select";
import TextInputFormComponentAlpinePlugin from "../../vendor/filament/forms/resources/js/components/text-input";

Alpine.plugin(SelectFormComponentAlpinePlugin);
Alpine.plugin(TextInputFormComponentAlpinePlugin);

Alpine.plugin(Focus);

window.Alpine = Alpine;

Alpine.start();
