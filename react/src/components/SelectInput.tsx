import { useEffect, useState } from "react";

const SelectInput = ({ label, name, touched = false, choices, error = "", children, ...props }: SelectInputType) => {
    const [isError, setIsError] = useState(false);

    useEffect(() => {
        setIsError(touched && "" !== error);
    }, [touched, error]);

    return (
        <div className="input-group">
            <label className="input-label" htmlFor={name}>
                {label}
            </label>
            <select {...props} name={name} className={`${isError ? "invalid" : ""} ${props.className ?? ""}`}>
                <option value="" />
                {choices.map((choice, index) => (
                    <option value={choice} key={choice + index}>
                        {choice}
                    </option>
                ))}
            </select>
            <span className="input-error">{isError && error}</span>
            {children}
        </div>
    );
};

export default SelectInput;

export interface SelectInputType {
    label: string;
    name: string;
    touched?: boolean;
    choices: Array<string>;
    error?: string;
    children?: any;
    [propName: string]: any;
}
