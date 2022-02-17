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
import Icon from '@/Shared/Icon';

const Edit = () => {
  const { contact } = usePage().props;
  const { data, setData, errors, put, processing } = useForm({
    first_name: contact.first_name || '',
    last_name: contact.last_name || '',
    nif_bi: contact.nif_bi || '',
    email: contact.email || '',
    phone: contact.phone || '',
    alternative_phone: contact.alternative_phone || '',
    cantina: contact.cantina || '',
    municipality: contact.municipality || '',
    district: contact.district || '',
    street: contact.street || '',
    estado: contact.estado || '',
    imei: contact.imei || ''
  });

  function handleSubmit(e) {
    e.preventDefault();
    put(route('contacts.update', contact.id));
  }

  function destroy() {
    if (confirm('Você tem certeza que deseja eliminar este parceiro?')) {
      Inertia.delete(route('contacts.destroy', contact.id));
    }
  }

  function restore() {
    if (confirm('Tem certeza que deseja restaurar esse parceiro?')) {
      Inertia.put(route('contacts.restore', contact.id));
    }
  }

  const pct = ['ALUMÍNIO', 'BRONZE', 'OURO'];

  var date = new Date();
  var dataActual = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0') + '-' + ((date.getDate() < '10' ? '0' : '') + date.getDate());

  var amanha = new Date(date.getTime());
  amanha.setDate(amanha.getDate() + 1);

  var dd = amanha.getDate();
  var mm = amanha.getMonth() + 1;
  var yyyy = amanha.getFullYear();

  if (dd < 10) {
    dd = '0' + dd;
  }

  if (mm < 10) {
    mm = '0' + mm;
  }

  var dataAmanha = yyyy + '-' + mm + '-' + dd;

  return (
    <div>
      <Helmet title={`${data.first_name} ${data.last_name}`} />
      <h1 className="mb-8 text-3xl font-bold">
        <InertiaLink
          href={route('contacts')}
          className="text-indigo-600 hover:text-indigo-700"
        >
          Parceiro
        </InertiaLink>
        <span className="mx-2 font-medium text-indigo-600">/</span>
        {data.first_name} {data.last_name} /{' '}
        <span
          className={`${
            data.estado == '0' ? 'text-red-400' : 'text-green-400'
          }`}
        >
          {data.estado == '0' ? 'Desactivo' : 'Activo'}
        </span>
      </h1>
      {contact.deleted_at && (
        <TrashedMessage onRestore={restore}>
          Este parceiro foi eliminado.
        </TrashedMessage>
      )}
      <div className="max-w-3xl overflow-hidden bg-white rounded shadow">
        <form onSubmit={handleSubmit}>
          <div className="flex flex-wrap p-8 -mb-8 -mr-6">
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Nome"
              name="first_name"
              errors={errors.first_name}
              value={data.first_name}
              onChange={e => setData('first_name', e.target.value)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Sobrenome"
              name="last_name"
              errors={errors.last_name}
              value={data.last_name}
              onChange={e => setData('last_name', e.target.value)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="NIF/BI"
              name="nif_bi"
              type="text"
              errors={errors.nif_bi}
              value={data.nif_bi}
              onChange={e => setData('nif_bi', e.target.value)}
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
              name="phone"
              type="text"
              errors={errors.phone}
              value={data.phone}
              onChange={e => setData('phone', e.target.value)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Telefone alternativo"
              name="alternative_phone"
              type="text"
              errors={errors.alternative_phone}
              value={data.alternative_phone}
              onChange={e => setData('alternative_phone', e.target.value)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Cantina"
              name="cantina"
              errors={errors.cantina}
              value={data.cantina}
              onChange={e => setData('cantina', e.target.value)}
            />
            <SelectInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Município"
              name="municipality"
              errors={errors.municipality}
              value={data.municipality}
              onChange={e => setData('municipality', e.target.value)}
            >
              <option value=""></option>
              <option value="Luanda">LUANDA</option>
              <option value="Belas">BELAS</option>
              <option value="Cazenga">CAZENGA</option>
              <option value="Cacuaco">CACUACO</option>
              <option value="Viana">VIANA</option>
              <option value="Icolo e Bengo">ICOLO E BENGO</option>
              <option value="Quissama">QUISSAMA</option>
              <option value="Talatona">TALATONA</option>
              <option value="Quilamba Quiaxi">QUILAMBA QUIAXI</option>
            </SelectInput>
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Bairro"
              name="district"
              type="text"
              errors={errors.district}
              value={data.district}
              onChange={e => setData('district', e.target.value)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Rua"
              name="street"
              type="text"
              errors={errors.street}
              value={data.street}
              onChange={e => setData('street', e.target.value)}
            />
            <SelectInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Estado"
              name="estado"
              errors={errors.estado}
              value={data.estado}
              onChange={e => setData('estado', e.target.value)}
            >
              <option value="1">ACTIVO</option>
              <option value="0">DESACTIVO</option>
            </SelectInput>
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="IMEI/Código de Série"
              name="imei"
              type="text"
              errors={errors.imei}
              value={data.imei}
              onChange={e => setData('imei', e.target.value)}
            />
          </div>
          <div className="flex items-center px-8 py-4 bg-gray-100 border-t border-gray-200">
            {!contact.deleted_at && (
              <DeleteButton onDelete={destroy}>Eliminar parceiro</DeleteButton>
            )}
            <LoadingButton
              loading={processing}
              type="submit"
              className="ml-auto btn-indigo"
            >
              Actualizar parceiro
            </LoadingButton>
          </div>
        </form>
      </div>
      <h2 className="mt-12 text-2xl font-bold">Os últimos 10 Pagamentos</h2>
      <div className="mt-6 overflow-x-auto bg-white rounded shadow">
        <table className="w-full whitespace-nowrap">
          <thead>
            <tr className="font-bold text-left">
              <th className="px-6 pt-5 pb-4">Pacote</th>
              <th className="px-6 pt-5 pb-4">Início</th>
              <th className="px-6 pt-5 pb-4">Fim</th>
            </tr>
          </thead>
          <tbody>
            {contact.pagamentos.map(
              ({ id, pacote, inicio, fim, deleted_at }) => {
                return (
                  <tr
                    key={id}
                    className={`hover:bg-gray-100 focus-within:bg-gray-100 ${
                      Date.parse(fim) <= Date.parse(dataActual) ? 'bg-red-100' : Date.parse(fim) == Date.parse(dataAmanha)?'bg-yellow-400':'bg-green-200'
                    }`}
                  >
                    <td className="border-t">
                      <InertiaLink
                        href={route('pagamentos.edit', id)}
                        className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                      >
                        {pct[pacote]}{Date.parse(fim) <= Date.parse(dataActual) ? ' (Terminado) ' : ''} {Date.parse(fim) == Date.parse(dataAmanha) ? ' (Termina amanhã) ' : ''}
                        {deleted_at && (
                          <Icon
                            name="trash"
                            className="flex-shrink-0 w-3 h-3 ml-2 text-gray-400 fill-current"
                          />
                        )}
                      </InertiaLink>
                    </td>
                    <td className="border-t">
                      <InertiaLink
                        tabIndex="-1"
                        href={route('pagamentos.edit', id)}
                        className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                      >
                        {inicio}
                      </InertiaLink>
                    </td>
                    <td className="border-t">
                      <InertiaLink
                        tabIndex="-1"
                        href={route('pagamentos.edit', id)}
                        className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                      >
                        {fim}
                      </InertiaLink>
                    </td>
                    <td className="w-px border-t">
                      <InertiaLink
                        tabIndex="-1"
                        href={route('pagamentos.edit', id)}
                        className="flex items-center px-4"
                      >
                        <Icon
                          name="cheveron-right"
                          className="block w-6 h-6 text-gray-400 fill-current"
                        />
                      </InertiaLink>
                    </td>
                  </tr>
                );
              }
            )}
            {contact.pagamentos.length === 0 && (
              <tr>
                <td className="px-6 py-4 border-t" colSpan="4">
                  Não foram encontrados pagamentos.
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
};

Edit.layout = page => <Layout children={page} />;

export default Edit;
