import React from 'react';

export default ({ label, name, errors = [], ...props }) => {
    return (
        <div>
            <label htmlFor={label} className="block mt-1 text-sm font-medium text-gray-700">
                {label}
            </label>
            <div className="mt-1">
                <textarea
                    id={label}
                    name={name}
                    rows={3}
                    {...props}
                    className={`mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm form-input ${errors.length ? 'error' : ''}`}
                />
            </div>
            <p className="mt-2 text-sm text-gray-500">
                ====================================
                {errors && (
                    <div className="form-error">{errors}</div>
                )}
            </p>
        </div>
    );
};
