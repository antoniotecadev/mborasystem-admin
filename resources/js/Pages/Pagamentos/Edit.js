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
import { tipoPacote, currency } from '@/Util/utilitario';

const Edit = () => {
  const { pagamento, contacts } = usePage().props;
  const { data, setData, errors, put, processing } = useForm({
    id: pagamento.id || '',
    pacote: pagamento.pacote || '',
    tipo_pagamento: pagamento.tipo_pagamento || '',
    preco: pagamento.preco || '',
    inicio: pagamento.inicio || '',
    fim: pagamento.fim || '',
    pagamento: pagamento.pagamento || '',
    contact_id: pagamento.contact_id,
    created_at: pagamento.created_at || ''
  });

  function handleSubmit(e) {
    e.preventDefault();
    put(route('pagamentos.update', pagamento.id));
  }

  function destroy() {
    if (confirm('Você tem certeza que deseja eliminar este pagamento?')) {
      var motivo = prompt('Qual é o motivo de sua eliminação?');
      if (motivo) {
        if (motivo.length > 150) {
          alert('⚠ Só é permitido 150 caracteres');
        } else {
          Inertia.delete(route('pagamentos.destroy', [pagamento.id, motivo]));
        }
      }
    }
  }

  function restore() {
    if (confirm('Tem certeza que deseja restaurar esse pagamento?')) {
      Inertia.put(route('pagamentos.restore', pagamento.id));
    }
  }

  const precopacote = tipoPacote(Number(data.pacote), Number(data.tipo_pagamento));

  return (
    <div>
      <Helmet title={`${data.inicio} ${data.fim}`} />
      <h1 className="mb-8 text-3xl font-bold">
        <InertiaLink
          href={route('pagamentos')}
          className="text-indigo-600 hover:text-indigo-700"
        >
          Pagamentos
        </InertiaLink>
        <span className="mx-2 font-medium text-indigo-600">/</span>
        {data.inicio} - {data.fim}
      </h1>
      {pagamento.deleted_at && (
        <TrashedMessage onRestore={restore}>
          <p>Este pagamento foi eliminado.{' '}<DeleteButton onDelete={e => alert(pagamento.motivo_elimina)}>Motivo</DeleteButton></p>
        </TrashedMessage>
      )}
      <div className="overflow-x-auto bg-white rounded shadow">
        <table className="w-full whitespace-nowrap">
          <thead>
            <tr className="font-bold text-left">
              <th className="px-6 pt-5 pb-4">Pacote/Tipo</th>
              <th className="px-6 pt-5 pb-4">Bronze</th>
              <th className="px-6 pt-5 pb-4">Alumínio</th>
              <th className="px-6 pt-5 pb-4">Ouro</th>
            </tr>
          </thead>
          <tbody>
            <tr key={0} className="hover:bg-gray-100 focus-within:bg-gray-100">
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none font-bold">MENSAL</p></td>
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">{currency(tipoPacote(0, 1))}</p></td>
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">{currency(tipoPacote(1, 1))}</p></td>
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">{currency(tipoPacote(2, 1))}</p></td>
            </tr>
            <tr key={1} className="hover:bg-gray-100 focus-within:bg-gray-100">
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none font-bold">TRIMESTRAL</p></td>
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">{currency(tipoPacote(0, 3))}</p></td>
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">{currency(tipoPacote(1, 3))}</p></td>
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">{currency(tipoPacote(2, 3))}</p></td>
            </tr>
            <tr key={2} className="hover:bg-gray-100 focus-within:bg-gray-100">
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none font-bold">SEMESTRAL</p></td>
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">{currency(tipoPacote(0, 6))}</p></td>
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">{currency(tipoPacote(1, 6))}</p></td>
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">{currency(tipoPacote(2, 6))}</p></td>
            </tr>
            <tr key={3} className="hover:bg-gray-100 focus-within:bg-gray-100">
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none font-bold">ANUAL</p></td>
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">{currency(tipoPacote(0, 12))}</p></td>
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">{currency(tipoPacote(1, 12))}</p></td>
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">{currency(tipoPacote(2, 12))}</p></td>
            </tr>
          </tbody>
        </table>
      </div>
      <br />
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
            <SelectInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label={"Preço: " + precopacote}
              name="preco"
              errors={errors.preco}
              value={data.preco}
              onChange={e => setData('preco', e.target.value)}
            >
              <option value="">Seleccionar preço</option>
              <option value={precopacote}>{precopacote}</option>
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
            <SelectInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Pagamento"
              name="pagamento"
              errors={errors.pagamento}
              value={data.pagamento}
              onChange={e => setData('pagamento', e.target.value)}
            >
              <option value=""></option>
              <option value="0">NORMAL</option>
              <option value="1">DE REGISTO</option>
            </SelectInput>
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Criado"
              type="text"
              value={data.created_at}
              readOnly
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
