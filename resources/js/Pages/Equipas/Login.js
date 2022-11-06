import React from 'react';
import Helmet from 'react-helmet';
import { InertiaLink, useForm, usePage } from '@inertiajs/inertia-react';
import LoadingButton from '@/Shared/LoadingButton';
import TextInput from '@/Shared/TextInput';
import { isEmpty } from 'lodash';

export default () => {
    const { id, codigo, error } = usePage().props;
    const { data, setData, errors, get, processing } = useForm({
        codigo: codigo || '',
        password: ''
    });

    function handleSubmit(e) {
        if (isEmpty(data.codigo)) {
            alert("⚠ Código YOGA não é válido.");
        } else if (isEmpty(data.password)) {
            alert("⚠ Palavra passe não é válida.");
        } else {
            e.preventDefault();
            get(route('api.login.equipa', [id, data.codigo, data.password]));
        }
    }

    return (
        <div className="flex items-center justify-center min-h-screen p-6 bg-indigo-900">
            <Helmet title="Login | Equipa" />
            <div className="w-full max-w-md">
                {/* <Logo
          className="block w-30 max-w-xs mx-auto text-white fill-current h-20"
          height={50}
        /> */}
                {/* <p className='block w-full max-w-xs mx-auto text-white fill-current text-2xl text-center'>MBORASYSTEM ADMIN</p> */}
                <form
                    onSubmit={handleSubmit}
                    className="mt-8 overflow-hidden bg-white rounded-lg shadow-xl"
                >
                    <div className="px-10 py-5">
                        <InertiaLink
                            href={route('api.lista.equipas')}
                            className="text-indigo-600 hover:text-indigo-700">
                            <h1 className="text-3xl text-center mt-5"> {'<- '} EQUIPAS YOGA</h1>
                        </InertiaLink>
                        <div className="w-24 mx-auto mt-6 border-b-2" />
                        <TextInput
                            className="mt-10"
                            label="Código"
                            name="codigo"
                            type="text"
                            errors={errors.codigo}
                            value={data.codigo}
                            onChange={e => setData('email', e.target.value)}
                        />
                        <TextInput
                            className="mt-6"
                            label="Palavra passe"
                            name="password"
                            type="password"
                            errors={error ? error : ''}
                            value={data.password}
                            onChange={e => setData('password', e.target.value)}
                        />
                    </div>
                    <div className="flex items-center justify-between px-10 py-4 bg-gray-100 border-t border-gray-200">
                        <a className="hover:underline" tabIndex="-1" href="#reset-password">
                            {/* Esqueceu a senha? */}
                        </a>
                        <LoadingButton
                            type="submit"
                            loading={processing}
                            className="btn-indigo"
                        >
                            Entrar
                        </LoadingButton>
                    </div>
                </form>
            </div>
        </div>
    );
};
