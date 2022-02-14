import React from 'react';
import { Inertia } from '@inertiajs/inertia';
import { InertiaLink, usePage, useForm } from '@inertiajs/inertia-react';
import Layout from '@/Shared/Layout';
import LoadingButton from '@/Shared/LoadingButton';
import TextInput from '@/Shared/TextInput';
import SelectInput from '@/Shared/SelectInput';

const Create = () => {
  const { data, setData, errors, post, processing } = useForm({
    codigo: '',
    estado: '0'
  });

  function handleSubmit(e) {
    e.preventDefault();
    post(route('equipas.store'));
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
              errors={errors.codigo}
              value={data.codigo}
              onChange={e => setData('codigo', e.target.value)}
            />
            <div className="w-full pb-4 pr-6">
              <label className ="mr-1" htmlFor='activo' >Activo</label>
              <input type="radio" id='activo' name='estado' value='1' onChange={e => setData('estado', e.target.value)}/>
              <label htmlFor='desactivo' className ="ml-4 mr-1">Desactivo</label>
              <input type="radio" checked id='desactivo' name='estado' value='0' onChange={e => setData('estado', e.target.value)}/>
              <br/> {errors.estado && <div className="form-error">{errors.estado}</div>}
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
