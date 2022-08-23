import { useEffect, useState } from "react";

const TextInput = ({ label, name, touched = true, type, error = "", children, ...props }: TextInputType) => {
    const [isError, setIsError] = useState(false);

    useEffect(() => {
        setIsError(Boolean(touched && "" !== error));
    }, [touched, error]);

    return (
        <div className="input-group">
            <label className="input-label" htmlFor={name}>
                {label}
            </label>
            <input {...props} className={`${isError ? "invalid" : ""} ${props.className ?? ""}`} name={name} id={name} type={type} />
            <span className="input-error">{isError && error}</span>
            {children}
        </div>
    );
};

export default TextInput;

export interface TextInputType {
    label: string;
    name: string;
    touched?: boolean;
    type: "text" | "tel" | "email" | "password";
    error?: string;
    children?: any;
    [propName: string]: any;
}
