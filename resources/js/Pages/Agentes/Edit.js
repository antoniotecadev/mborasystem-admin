import React, { useState } from 'react';
import Helmet from 'react-helmet';
import { Inertia } from '@inertiajs/inertia';
import { InertiaLink, usePage, useForm } from '@inertiajs/inertia-react';
import Layout from '@/Shared/Layout';
import DeleteButton from '@/Shared/DeleteButton';
import LoadingButton from '@/Shared/LoadingButton';
import TextInput from '@/Shared/TextInput';
import SelectInput from '@/Shared/SelectInput';
import TrashedMessage from '@/Shared/TrashedMessage';

const Edit = () => {
  const [senha, setSenha] = useState(false);
  const { agente, equipas } = usePage().props;
  const { data, setData, errors, put, processing } = useForm({
    nome_completo: agente.nome_completo || '',
    email: agente.email || '',
    telefone: agente.telefone || '',
    telefone_alternativo: agente.telefone_alternativo || '',
    municipio: agente.municipio || '',
    bairro: agente.bairro || '',
    rua: agente.rua || '',
    banco: agente.banco || '',
    estado: agente.estado || '',
    senha: agente.senha || '',
    equipa_id: agente.equipa_id || ''
  });

  function handleSubmit(e) {
    e.preventDefault();
    put(route('agentes.update', agente.id));
  }

  function destroy() {
    if (confirm('Você tem certeza que deseja eliminar este pagamento?')) {
      Inertia.delete(route('agentes.destroy', agente.id));
    }
  }

  function restore() {
    if (confirm('Tem certeza que deseja restaurar esse agente?')) {
      Inertia.put(route('agentes.restore', agente.id));
    }
  }

  return (
    <div>
      <Helmet title={`${data.nome_completo}`} />
      <h1 className="mb-8 text-3xl font-bold">
        <InertiaLink
          href={route('agentes')}
          className="text-indigo-600 hover:text-indigo-700"
        >
          Agentes {agente.id}
        </InertiaLink>
        <span className="mx-2 font-medium text-indigo-600">/</span>
        {data.nome_completo}
      </h1>
      {agente.deleted_at && (
        <TrashedMessage onRestore={restore}>
          Este agente foi eliminado.
        </TrashedMessage>
      )}
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
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Senha"
              name="senha"
              type={`${senha ? 'text' : 'password'}`}
              errors={errors.senha}
              value={data.senha}
              onChange={e => setData('senha', e.target.value)}
            />
            <div className="w-full pb-4 pr-6 ml-2">
              <input type="checkbox" id='senha' onChange={e => setSenha(!senha)}/>
            </div>
            <div className="w-full pb-4 pr-6 ml-2">
                <label className ="mr-1" htmlFor='activo' >Activo</label>
                {data.estado == '1' ?
                <input type="radio" checked id='activo' name='estado' value='1' onChange={e => setData('estado', e.target.value)}/>
                :
                <input type="radio" id='activo' name='estado' value='1' onChange={e => setData('estado', e.target.value)}/>}

                <label htmlFor='desactivo' className ="ml-4 mr-1">Desactivo</label>
                {data.estado == '1' ?
                <input type="radio" id='desactivo' name='estado' value='0' onChange={e => setData('estado', e.target.value)}/>
                :
                <input type="radio" checked id='desactivo' name='estado' value='0' onChange={e => setData('estado', e.target.value)}/>}
                <br/> {errors.estado && <div className="form-error">{errors.estado}</div>}
            </div>
          </div>
          <div className="flex items-center px-8 py-4 bg-gray-100 border-t border-gray-200">
            {!agente.deleted_at && (
              <DeleteButton onDelete={destroy}>Eliminar agente</DeleteButton>
            )}
            <LoadingButton
              loading={processing}
              type="submit"
              className="ml-auto btn-indigo"
            >
              Actualizar agente
            </LoadingButton>
          </div>
        </form>
      </div>
    </div>
  );
};

Edit.layout = page => <Layout children={page} />;

export default Edit;
