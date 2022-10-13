import React from 'react';

export default ({ label1, label2, name, errors = [], ...props }) => {
    return (
        <div className="w-full pb-4 pr-6">
            {label1 && (
                <label className="mr-1 text-sm font-medium text-gray-700" htmlFor={label1}>
                    {label1}
                </label>
            )}
            <input
                type="radio"
                id={label1}
                name={name}
                value="1"
                {...props}
                className={`${errors.length ? 'error' : ''} h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500`}
            />
            {label2 && (
                <label className="ml-4 mr-1 text-sm font-medium text-gray-700" htmlFor={label2}>
                    {label2}
                </label>
            )}
            <input
                type="radio"
                checked
                id={label2}
                name={name}
                value="0"
                {...props}
                className={`${errors.length ? 'error' : ''} h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500`}
            />
            <br />{' '}
            {errors && <div className="form-error">{errors}</div>}
        </div>
    );
};
