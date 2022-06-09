import React, { useState } from 'react';
import { InertiaLink, usePage, useForm } from '@inertiajs/inertia-react';
import Layout from '@/Shared/Layout';
import LoadingButton from '@/Shared/LoadingButton';
import TextInput from '@/Shared/TextInput';
import Icon from '@/Shared/Icon';

const Create = () => {
  const [senha, setSenha] = useState(false);
  const { data, setData, errors, post, processing } = useForm({
    codigo: '',
    estado: '0',
    password: ''
  });

  function handleSubmit(e) {
    e.preventDefault();
    post(route('equipas.store'));
  }

  function gerarNumeroAleatorio(e) {
    e.preventDefault();
    let codigo = Math.floor(Math.random() * (999999 - 100000)) + 100000;
    setData('codigo', codigo);
  }

  return (
    <div>
      <h1 className="mb-8 text-3xl font-bold">
        <InertiaLink
          href={route('equipas')}
          className="text-indigo-600 hover:text-indigo-700"
        >
          Equipas
        </InertiaLink>
        <span className="font-medium text-indigo-600"> /</span> Criar
      </h1>
      <div className="max-w-3xl overflow-hidden bg-white rounded shadow">
        <form onSubmit={handleSubmit}>
          <div className="flex flex-wrap p-8 -mb-8 -mr-6">
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Código"
              name="codigo"
              type="text"
              errors={errors.codigo}
              value={data.codigo}
              onChange={e => setData('codigo', e.target.value)}
              readOnly
            />
            <div className="flex items-center justify-end mb-2">
              <LoadingButton
                loading={processing}
                onClick={gerarNumeroAleatorio}
                className="btn-indigo"
              >
                <Icon name='actualizar' />
              </LoadingButton>
              <div className="w-full pb-4 pr-6 ml-6 mt-4">
                <label className="mr-1" htmlFor='activo' >Activo</label>
                <input type="radio" id='activo' name='estado' value='1' onChange={e => setData('estado', e.target.value)} />
                <label htmlFor='desactivo' className="ml-4 mr-1">Desactivo</label>
                <input type="radio" checked id='desactivo' name='estado' value='0' onChange={e => setData('estado', e.target.value)} />
                <br /> {errors.estado && <div className="form-error">{errors.estado}</div>}
              </div>
            </div>
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Palavra passe"
              name="password"
              type={`${senha ? 'text' : 'password'}`}
              errors={errors.password}
              value={data.password}
              onChange={e => setData('password', e.target.value)}
            />
            <div className="flex items-center justify-end mb-2">
              <div className="w-full pb-4 pr-6 mt-4">
                <label htmlFor="password" className="mr-4">
                  {senha ? 'Ocultar' : 'Visualizar'}
                </label>
                <input
                  type="checkbox"
                  id="password"
                  onChange={e => setSenha(!senha)}
                />
              </div>
            </div>
          </div>
          <div className="flex items-center justify-end px-8 py-4 bg-gray-100 border-t border-gray-200">
            <LoadingButton
              loading={processing}
              type="submit"
              className="btn-indigo"
            >
              Criar equipa
            </LoadingButton>
          </div>
        </form>
      </div>
    </div>
  );
};

Create.layout = page => <Layout title="Criar equipa" children={page} />;

export default Create;
