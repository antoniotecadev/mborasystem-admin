import React, { Fragment } from 'react';
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
  const { pagamento, contacts } = usePage().props;
  const { data, setData, errors, put, processing } = useForm({
    pacote: pagamento.pacote || '',
    tipo_pagamento: pagamento.tipo_pagamento || '',
    inicio: pagamento.inicio || '',
    fim: pagamento.fim || '',
    contact_id: pagamento.contact_id
  });

  function handleSubmit(e) {
    e.preventDefault();
    put(route('pagamentos.update', pagamento.id));
  }

  function destroy() {
    if (confirm('Você tem certeza que deseja eliminar este pagamento?')) {
      Inertia.delete(route('pagamentos.destroy', pagamento.id));
    }
  }

  function restore() {
    if (confirm('Tem certeza que deseja restaurar esse pagamento?')) {
      Inertia.put(route('pagamentos.restore', pagamento.id));
    }
  }

  return (
    <div>
      <Helmet title={`${data.inicio} ${data.fim}`} />
      <h1 className="mb-8 text-3xl font-bold">
        <InertiaLink
          href={route('pagamentos')}
          className="text-indigo-600 hover:text-indigo-700"
        >
          Pagamento {pagamento.id}
        </InertiaLink>
        <span className="mx-2 font-medium text-indigo-600">/</span>
        {data.inicio} - {data.fim}
      </h1>
      {pagamento.deleted_at && (
        <TrashedMessage onRestore={restore}>
          Este pagamento foi eliminado.
        </TrashedMessage>
      )}
      <div className="max-w-3xl overflow-hidden bg-white rounded shadow">
        <form onSubmit={handleSubmit}>
          <div className="flex flex-wrap p-8 -mb-8 -mr-6">
          <SelectInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Parceiro"
              name="contact_id"
              errors={errors.contact_id}
              value={data.contact_id}
              onChange={e => setData('contact_id', e.target.value)}>
             <option value=""></option>
              {contacts.map(({ id, first_name, last_name, cantina, phone }) => (
                <option key={id} value={id}>
                  {first_name} {last_name} - {cantina} - {phone}
                </option>
              ))}
            </SelectInput>
            <SelectInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Pacote"
              name="pacote"
              errors={errors.pacote}
              value={data.pacote}
              onChange={e => setData('pacote', e.target.value)}>
              <option value="0">BRONZE</option>
              <option value="1">ALUMÍNIO</option>
              <option value="2">OURO</option>
            </SelectInput>
            <SelectInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Tipo"
              name="tipo_pagamento"
              errors={errors.tipo_pagamento}
              value={data.tipo_pagamento}
              onChange={e => setData('tipo_pagamento', e.target.value)}
            >
              <option value="1">MENSAL</option>
              <option value="3">TRIMESTRAL</option>
              <option value="6">SEMESTRAL</option>
              <option value="12">ANUAL</option>
            </SelectInput>
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Início"
              name="inicio"
              type="date"
              errors={errors.inicio}
              value={data.inicio}
              onChange={e => setData('inicio', e.target.value)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Fim"
              name="fim"
              type="date"
              errors={errors.fim}
              value={data.fim}
              onChange={e => setData('fim', e.target.value)}
            />
          </div>
          <div className="flex items-center px-8 py-4 bg-gray-100 border-t border-gray-200">
            {!pagamento.deleted_at && (
              <DeleteButton onDelete={destroy}>Eliminar pagamento</DeleteButton>
            )}
            <LoadingButton
              loading={processing}
              type="submit"
              className="ml-auto btn-indigo"
            >
              Actualizar pagamento
            </LoadingButton>
          </div>
        </form>
      </div>
    </div>
  );
};

Edit.layout = page => <Layout children={page} />;

export default Edit;
