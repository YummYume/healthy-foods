export const initialFormData: FormDataType = {
    username: {
        value: "",
        error: "",
        touched: false,
        requirements: {
            empty: false,
            emptyMessage: "Veuillez saisir un nom d'utilisateur."
        }
    },
    email: {
        value: "",
        error: "",
        touched: false,
        requirements: {
            empty: false,
            emptyMessage: "Veuillez saisir une adresse email.",
            regex: /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/,
            regexMessage: "Veuillez saisir une adresse email valide."
        }
    },
    phoneNumber: {
        value: "",
        error: "",
        touched: false,
        requirements: {
            empty: true,
            regex: /^$|^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im,
            regexMessage: "Veuillez saisir un numéro de téléphone valide."
        }
    },
    password: {
        value: "",
        error: "",
        touched: false,
        requirements: {
            empty: false,
            emptyMessage: "Veuillez saisir un mot de passe.",
            regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/,
            regexMessage:
                "Votre mot de passe doit contenir au moins 8 caractères, parmis lequels une minuscule, une majuscule, un caractère spécial et un chiffre."
        }
    },
    city: {
        value: "",
        error: "",
        touched: false,
        requirements: {
            empty: false,
            emptyMessage: "Veuillez selectionner une ville."
        }
    }
};

export interface FormDataType {
    [key: string]: FieldDataType;
}

export interface FieldDataType {
    value: string;
    error: string;
    touched: boolean;
    requirements?: FieldRequirementsType;
}

export interface FieldRequirementsType {
    empty?: boolean;
    emptyMessage?: string;
    regex?: RegExp;
    regexMessage?: string;
}
