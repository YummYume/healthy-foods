import { writable } from "svelte/store";

import { initialFormData, type FormDataType } from "../data/formData";

function handleErrors(formData: FormDataType): FormDataType {
    for (const field in formData) {
        const fieldData = formData[field];
        const value = fieldData.value;
        const requirements = fieldData.requirements;
        const regexRequirement = requirements?.regex ? new RegExp(requirements.regex) : null;

        let error = "";

        if (requirements) {
            if (false === requirements.empty && "" === value) {
                error = requirements.emptyMessage ?? "Cette valeur ne peut pas être vide.";
            } else if (regexRequirement && !regexRequirement.test(value)) {
                error = requirements.regexMessage ?? "Cette valeur n'est pas valide.";
            }
        }

        formData[field] = { ...formData[field], error };
    }

    return formData;
}

function touched(formData: FormDataType, key: string): FormDataType {
    if (key in formData) {
        formData[key].touched = true;
    }

    return formData;
}

function createAccountData() {
    const { subscribe, set, update } = writable<FormDataType>(handleErrors(initialFormData));

    return {
        subscribe,
        set,
        setTouched: (key: string) => update((f) => touched(f, key)),
        handleErrors: () => update((f) => handleErrors(f))
    };
}

export const accountData = createAccountData();
