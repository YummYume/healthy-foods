import { useState } from "react";

import SelectInput from "./components/SelectInput";
import TextInput from "./components/TextInput";
import { FormDataType, initialFormData } from "./data/formData";

function App() {
    const cities: Array<string> = ["Compiègne", "Paris", "Amiens"];
    const [isError, setIsError] = useState<boolean>(true);
    const [formData, setFormData] = useState<FormDataType>(initialFormData);

    const handleErrors = (formData: FormDataType) => {
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
    };

    const handleChange = (event: React.FormEvent<HTMLInputElement | HTMLSelectElement>) => {
        const target = event.target as HTMLInputElement;
        let newFormData = formData;

        if (target.name in newFormData) {
            const value = target.value;

            newFormData = { ...newFormData, [target.name]: { ...newFormData[target.name], value, touched: true } };
        }

        newFormData = handleErrors(newFormData);

        setFormData(newFormData);
        setIsError(Object.values(newFormData).some((d) => "" !== d.error));
    };

    const onSubmit = (event: React.SyntheticEvent) => {
        event.preventDefault();

        if (!isError) {
            alert(JSON.stringify(Object.values(formData).map((d) => d.value)));
        }
    };

    return (
        <div className="app">
            <h1>React</h1>
            <form id="form" onSubmit={onSubmit}>
                <TextInput
                    name="username"
                    value={formData.username.value}
                    error={formData.username.error}
                    touched={formData.username.touched}
                    type="text"
                    label="Nom d'utilisateur"
                    placeholder="Jean"
                    onChange={handleChange}
                />
                <TextInput
                    name="email"
                    value={formData.email.value}
                    error={formData.email.error}
                    touched={formData.email.touched}
                    type="email"
                    label="Adresse email"
                    placeholder="jean@gmail.com"
                    onChange={handleChange}
                />
                <TextInput
                    name="phoneNumber"
                    value={formData.phoneNumber.value}
                    error={formData.phoneNumber.error}
                    touched={formData.phoneNumber.touched}
                    type="tel"
                    label="Numéro de téléphone"
                    placeholder="0690805542"
                    onChange={handleChange}
                />
                <TextInput
                    name="password"
                    value={formData.password.value}
                    error={formData.password.error}
                    touched={formData.password.touched}
                    type="password"
                    label="Mot de passe"
                    onChange={handleChange}
                />
                <SelectInput
                    name="city"
                    value={formData.city.value}
                    error={formData.city.error}
                    touched={formData.city.touched}
                    label="Ville"
                    choices={cities}
                    onChange={handleChange}
                />
            </form>
            <button type="submit" form="form" className={`${isError ? "disabled" : ""}`} disabled={isError}>
                Envoyer
            </button>
        </div>
    );
}

export default App;
