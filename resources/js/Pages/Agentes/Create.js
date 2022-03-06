import React from 'react';
import { InertiaLink, useForm, usePage } from '@inertiajs/inertia-react';
import Layout from '@/Shared/Layout';
import LoadingButton from '@/Shared/LoadingButton';
import TextInput from '@/Shared/TextInput';
import SelectInput from '@/Shared/SelectInput';

const Create = () => {
  const { equipas } = usePage().props;
  const { data, setData, errors, post, processing } = useForm({
    nome_completo: '',
    bi: '',
    email: '',
    telefone: '',
    telefone_alternativo: '',
    municipio: '',
    bairro: '',
    rua: '',
    banco: '',
    estado: '',
    equipa_id: ''
  });

  function handleSubmit(e) {
    e.preventDefault();
    post(route('agentes.store'));
  }

  return (
    <div>
      <h1 className="mb-8 text-3xl font-bold">
        <InertiaLink
          href={route('agentes')}
          className="text-indigo-600 hover:text-indigo-700"
        >
          Agentes
        </InertiaLink>
        <span className="font-medium text-indigo-600"> /</span> Criar
      </h1>
      <div className="max-w-3xl overflow-hidden bg-white rounded shadow">
        <form onSubmit={handleSubmit}>
          <div className="flex flex-wrap p-8 -mb-8 -mr-6">
            <SelectInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Equipa"
              name="equipa_id"
              errors={errors.equipa_id}
              value={data.equipa_id}
              onChange={e => setData('equipa_id', e.target.value)}
            >
              <option value=""></option>
              {equipas.map(({ id, codigo }) => (
                <option key={id} value={id}>
                  {codigo}
                </option>
              ))}
            </SelectInput>
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Nome completo"
              name="nome_completo"
              type="text"
              errors={errors.nome_completo}
              value={data.nome_completo}
              onChange={e => setData('nome_completo', e.target.value)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="B.I"
              name="bi"
              type="text"
              errors={errors.bi}
              value={data.bi}
              onChange={e => setData('bi', e.target.value)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Email"
              name="email"
              type="email"
              errors={errors.email}
              value={data.email}
              onChange={e => setData('email', e.target.value)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Telefone"
              name="telefone"
              type="text"
              errors={errors.telefone}
              value={data.telefone}
              onChange={e => setData('telefone', e.target.value)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Telefone alternativo"
              name="telefone_alternativo"
              type="text"
              errors={errors.telefone_alternativo}
              value={data.telefone_alternativo}
              onChange={e => setData('telefone_alternativo', e.target.value)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Município"
              name="municipio"
              type="text"
              errors={errors.municipio}
              value={data.municipio}
              onChange={e => setData('municipio', e.target.value)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Bairro"
              name="bairro"
              type="text"
              errors={errors.bairro}
              value={data.bairro}
              onChange={e => setData('bairro', e.target.value)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Rua"
              name="rua"
              type="text"
              errors={errors.rua}
              value={data.rua}
              onChange={e => setData('rua', e.target.value)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Banco"
              name="banco"
              type="text"
              errors={errors.banco}
              value={data.banco}
              onChange={e => setData('banco', e.target.value)}
              placeholder="CONTA - IBAN"
            />
            <div className="w-full pb-4 pr-6 ml-2">
                <label className ="mr-1" htmlFor='activo' >Activo</label>
                <input type="radio" id='activo' name='estado' value='1' onChange={e => setData('estado', e.target.value)}/>
                <label htmlFor='desactivo' className ="ml-4 mr-1">Desactivo</label>
                <input type="radio" id='desactivo' name='estado' value='0' onChange={e => setData('estado', e.target.value)}/>
                <br/> {errors.estado && <div className="form-error">{errors.estado}</div>}
            </div>
          </div>
          <div className="flex items-center justify-end px-8 py-4 bg-gray-100 border-t border-gray-200">
            <LoadingButton
              loading={processing}
              type="submit"
              className="btn-indigo"
            >
              Criar agente
            </LoadingButton>
          </div>
        </form>
      </div>
    </div>
  );
};

Create.layout = page => <Layout title="Criar agente" children={page} />;

export default Create;
