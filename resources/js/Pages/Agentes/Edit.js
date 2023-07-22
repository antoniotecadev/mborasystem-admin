import React from 'react';
import Helmet from 'react-helmet';
import { Inertia } from '@inertiajs/inertia';
import { InertiaLink, usePage, useForm } from '@inertiajs/inertia-react';
import Layout from '@/Shared/Layout';
import DeleteButton from '@/Shared/DeleteButton';
import LoadingButton from '@/Shared/LoadingButton';
import TextInput from '@/Shared/TextInput';
import SelectInput from '@/Shared/SelectInput';
import TextArea from '@/Shared/TextArea';
import TrashedMessage from '@/Shared/TrashedMessage';
import { alertToast } from '@/Util/utilitario';

const Edit = () => {
  const { agente, equipas } = usePage().props;
  const { data, setData, errors, put, processing } = useForm({
    nome_completo: agente.nome_completo || '',
    bi: agente.bi || '',
    email: agente.email || '',
    telefone: agente.telefone || '',
    telefone_alternativo: agente.telefone_alternativo || '',
    municipio: agente.municipio || '',
    bairro: agente.bairro || '',
    rua: agente.rua || '',
    banco: agente.banco || '',
    estado: agente.estado || '',
    equipa_id: agente.equipa_id || '',
    created_at: agente.created_at || ''
  });

  function handleSubmit(e) {
    e.preventDefault();
    if (agente.deleted_at) {
      alertToast("⚠ Agente eliminado não pode ser actualizado.", "update_agente");
    } else {
      put(route('agentes.update', agente.id));
    }
  }

  function destroy() {
    if (confirm('Você tem certeza que deseja eliminar este agente?')) {
      var motivo = prompt('Qual é o motivo de sua eliminação?');
      if (motivo) {
        if (motivo.length > 150) {
          alertToast("⚠ Só é permitido 150 caracteres", "max_caractere");
        } else {
          Inertia.delete(route('agentes.destroy', [agente.id, motivo]));
        }
      }
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
          Agentes
        </InertiaLink>
        <span className="mx-2 font-medium text-indigo-600">/</span>
        {data.nome_completo} /{' '}
        <span
          className={`${data.estado == '0' ? 'text-red-400' : 'text-green-400'
            }`}
        >
          {data.estado == '0' ? 'Desactivo' : 'Activo'}
        </span>
      </h1>
      {agente.deleted_at && (
        <TrashedMessage onRestore={restore}>
          <p>Este agente foi eliminado.{' '}<DeleteButton onDelete={e => alertToast(agente.motivo_elimina, "agente_motivo_elimina")}>Motivo</DeleteButton></p>
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
            <TextArea
              label="IBAN/NIB"
              name="banco"
              value={data.banco}
              onChange={e => setData('banco', e.target.value)}
              placeholder="BANCO: AO00 0000.0000.0000.0000.000.00"
              defaultValue={''}
              errors={errors.banco} />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Criado"
              type="text"
              value={data.created_at}
              readOnly
            />
            <div className="w-full pb-4 pr-6 ml-2">
              <label className="mr-1" htmlFor='activo' >Activo</label>
              {data.estado == '1' ?
                <input type="radio" checked id='activo' name='estado' value='1' onChange={e => setData('estado', e.target.value)} />
                :
                <input type="radio" id='activo' name='estado' value='1' onChange={e => setData('estado', e.target.value)} />}

              <label htmlFor='desactivo' className="ml-4 mr-1">Desactivo</label>
              {data.estado == '1' ?
                <input type="radio" id='desactivo' name='estado' value='0' onChange={e => setData('estado', e.target.value)} />
                :
                <input type="radio" checked id='desactivo' name='estado' value='0' onChange={e => setData('estado', e.target.value)} />}
              <br /> {errors.estado && <div className="form-error">{errors.estado}</div>}
            </div>
          </div>
          <div className="flex items-center px-8 py-4 bg-gray-100 border-t border-gray-200">
            {!agente.deleted_at && (
              <DeleteButton onDelete={destroy}>Eliminar Agente</DeleteButton>
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
