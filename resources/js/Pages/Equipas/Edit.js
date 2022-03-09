import React, { useState } from 'react';
import Helmet from 'react-helmet';
import { Inertia } from '@inertiajs/inertia';
import { InertiaLink, usePage, useForm } from '@inertiajs/inertia-react';
import Layout from '@/Shared/Layout';
import DeleteButton from '@/Shared/DeleteButton';
import LoadingButton from '@/Shared/LoadingButton';
import TextInput from '@/Shared/TextInput';
import TrashedMessage from '@/Shared/TrashedMessage';
import Icon from '@/Shared/Icon';
import { currency } from '@/Util/utilitario';
import { isUndefined } from 'lodash';

const Edit = () => {
  const [senha, setSenha] = useState(false);
  const { equipa, parceiros, valorcada, valortotal, valortotalbruto, iniciodata, fimdata, numeroagente, quantidade } = usePage().props;
  const [inicio, setInicio] = useState(iniciodata);
  const [fim, setFim] = useState(fimdata);
  const [numeroAgente, setNumeroAgente] = useState(3);
  const { data, setData, errors, put, post, processing } = useForm({
    codigo: equipa.codigo || '',
    estado: equipa.estado || '',
    password: '',
    created_at: equipa.created_at || ''
  });

  function handleSubmit(e) {
    e.preventDefault();
    put(route('equipas.update', equipa.id));
  }

  function destroy() {
    if (confirm('Você tem certeza que deseja eliminar esta equipa?')) {
      Inertia.delete(route('equipas.destroy', equipa.id));
    }
  }

  function restore() {
    if (confirm('Tem certeza que deseja restaurar essa equipa?')) {
      Inertia.put(route('equipas.restore', equipa.id));
    }
  }

  function calcular(e) {
    e.preventDefault();
    if(isUndefined(inicio)){
      alert('Data de início não definada');
    } else if (isUndefined(fim)){
      alert('Data de fim não definada');
    } else {
      Inertia.get(route('equipas.calcular', [equipa.id, equipa.codigo, inicio, fim, numeroAgente]));
    }
  }

  const pct = ['BRONZE', 'ALUMÍNIO', 'OURO']

  return (
    <div>
      <Helmet title={`${data.codigo}`} />
      <h1 className="mb-8 text-3xl font-bold">
        <InertiaLink
          href={route('equipas')}
          className="text-indigo-600 hover:text-indigo-700"
        >
          Equipas
        </InertiaLink>
        <span className="mx-2 font-medium text-indigo-600">/</span>
        {data.codigo} /{' '}
        <span
          className={`${
            data.estado == '0' ? 'text-red-400' : 'text-green-400'
          }`}
        >
          {data.estado == '0' ? 'Desactivo' : 'Activo'}
        </span>
      </h1>
      {equipa.deleted_at && (
        <TrashedMessage onRestore={restore}>
          Esta equipa foi eliminada.
        </TrashedMessage>
      )}
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
              <div className="w-full pb-4 pr-6 mt-4">
                <label className="mr-1" htmlFor="activo">
                  Activo
                </label>
                {data.estado == '1' ? (
                  <input
                    type="radio"
                    checked
                    id="activo"
                    name="estado"
                    value="1"
                    onChange={e => setData('estado', e.target.value)}
                  />
                ) : (
                  <input
                    type="radio"
                    id="activo"
                    name="estado"
                    value="1"
                    onChange={e => setData('estado', e.target.value)}
                  />
                )}
                <label htmlFor="desactivo" className="ml-4 mr-1">
                  Desactivo
                </label>
                {data.estado == '1' ? (
                  <input
                    type="radio"
                    id="desactivo"
                    name="estado"
                    value="0"
                    onChange={e => setData('estado', e.target.value)}
                  />
                ) : (
                  <input
                    type="radio"
                    checked
                    id="desactivo"
                    name="estado"
                    value="0"
                    onChange={e => setData('estado', e.target.value)}
                  />
                )}
                <br />{' '}
                {errors.estado && (
                  <div className="form-error">{errors.estado}</div>
                )}
              </div>
            </div>
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Senha"
              name="password"
              type={`${senha ? 'text' : 'password'}`}
              errors={errors.password}
              value={data.password}
              onChange={e => setData('password', e.target.value)}
            />
            <input
              type="checkbox"
              className="mb-6"
              id="password"
              onChange={e => setSenha(!senha)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Criada"
              type="text"
              value={data.created_at}
              readOnly
            />
          </div>
          <div className="flex items-center px-8 py-4 bg-gray-100 border-t border-gray-200">
            {!equipa.deleted_at && (
              <DeleteButton onDelete={destroy}>Eliminar equipa</DeleteButton>
            )}
            <LoadingButton
              loading={processing}
              type="submit"
              className="ml-auto btn-indigo"
            >
              Actualizar equipa
            </LoadingButton>
          </div>
        </form>
      </div>
      <h2 className="mt-12 text-2xl font-bold">Agentes</h2>
      <div className="mt-6 overflow-x-auto bg-white rounded shadow">
        <table className="w-full whitespace-nowrap">
          <thead>
            <tr className="font-bold text-left">
              <th className="px-6 pt-5 pb-4">Nome completo</th>
              <th className="px-6 pt-5 pb-4">Telefone</th>
              <th className="px-6 pt-5 pb-4">Email</th>
            </tr>
          </thead>
          <tbody>
            {equipa.agentes.map(
              ({ id, nome_completo, telefone, email, estado, deleted_at }) => {
                return (
                  <tr
                    key={id}
                    className={`hover:bg-gray-100 focus-within:bg-gray-100 ${
                      estado == '0' ? 'bg-red-100' : 'bg-green-200'
                    }`}
                  >
                    <td className="border-t">
                      <InertiaLink
                        href={route('agentes.edit', id)}
                        className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                      >
                        {nome_completo}
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
                        href={route('agentes.edit', id)}
                        className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                      >
                        {telefone}
                      </InertiaLink>
                    </td>
                    <td className="border-t">
                      <InertiaLink
                        tabIndex="-1"
                        href={route('agentes.edit', id)}
                        className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                      >
                        {email}
                      </InertiaLink>
                    </td>
                    <td className="w-px border-t">
                      <InertiaLink
                        tabIndex="-1"
                        href={route('agentes.edit', id)}
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
            {equipa.agentes.length === 0 && (
              <tr>
                <td className="px-6 py-4 border-t" colSpan="4">
                  Não foram encontrados agentes.
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>
      <h2 className="mt-12 text-2xl font-bold">Lucro ({iniciodata + ' - ' + fimdata})</h2>
      <div className="mt-6 max-w-3xl overflow-hidden bg-white rounded shadow">
        <form onSubmit={calcular}>
          <div className="flex flex-wrap p-8 -mb-8 -mr-6">
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label={"De: " + iniciodata}
              name="inicio"
              type="date"
              value={inicio}
              onChange={e => setInicio(e.target.value)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label={"Até: " + fimdata}
              name="fim"
              type="date"
              value={fim}
              onChange={e => setFim(e.target.value)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label={"Para: " + numeroagente}
              name="numero_agente"
              type="number"
              value={numeroAgente}
              onChange={e => setNumeroAgente(e.target.value)}
              min={1}
              max={4}
            />
          </div>
          <div className="flex items-center justify-end px-8 py-4 bg-gray-100 border-t border-gray-200">
            <LoadingButton
              className="btn-indigo"
              loading={processing}
              type="submit"
            >
              Calcular
            </LoadingButton>
          </div>
        </form>
      </div>
      <p></p>
      <div className="mt-6 overflow-x-auto bg-white rounded shadow">
        <table className="w-full whitespace-nowrap">
          <thead>
            <tr className="font-bold text-left">
              <th className="px-6 pt-5 pb-4">Percentagem</th>
              <th className="px-6 pt-5 pb-4">Valor (Para cada)</th>
              <th className="px-6 pt-5 pb-4">Valor total</th>
              <th className="px-6 pt-5 pb-4">Valor total (Bruto)</th>
            </tr>
          </thead>
          <tbody>
            <tr
              key="1"
              className="hover:bg-gray-100 focus-within:bg-gray-100 bg-green-200"
            >
              <td className="border-t">
                <InertiaLink className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">
                  26%
                </InertiaLink>
              </td>
              <td className="border-t">
                <InertiaLink
                  tabIndex="-1"
                  className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                >
                  {valorcada && currency(valorcada)}
                </InertiaLink>
              </td>
              <td className="border-t">
                <InertiaLink
                  tabIndex="-1"
                  className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                >
                  {valortotal && currency(valortotal)}
                </InertiaLink>
              </td>
              <td className="w-px border-t">
                <InertiaLink tabIndex="-1" className="flex items-center px-4">
                  {valortotalbruto && currency(valortotalbruto)}
                </InertiaLink>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <h2 className="mt-12 text-2xl font-bold">Parceiros ({quantidade})</h2>
      <div className="mt-6 overflow-x-auto bg-white rounded shadow">
        <table className="w-full whitespace-nowrap">
          <thead>
            <tr className="font-bold text-left">
              <th className="px-6 pt-5 pb-4">Parceiro</th>
              <th className="px-6 pt-5 pb-4">IMEI</th>
              <th className="px-6 pt-5 pb-4">Data (Parceiro)</th>
              <th className="px-6 pt-5 pb-4">Pacote</th>
              <th className="px-6 pt-5 pb-4">Preço</th>
              <th className="px-6 pt-5 pb-4">Data (Pagamento)</th>

            </tr>
          </thead>
          <tbody>
            {parceiros && parceiros.map(
              ({ idcontact, first_name, last_name, imei, read_contact, datacriacontact, pacote, preco, datacriapagamento }) => {
                return (
                  <tr
                    key={idcontact}
                    className='hover:bg-gray-100 focus-within:bg-gray-100 bg-yellow-200'>
                    <td className="border-t">
                      <InertiaLink
                        href={route('contacts.edit', [idcontact, 1, read_contact])}
                        className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                      >
                        {first_name +" "+ last_name}
                      </InertiaLink>
                    </td>
                    <td className="border-t">
                      <InertiaLink
                        tabIndex="-1"
                        href={route('contacts.edit', [idcontact, 1, read_contact])}
                        className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                      >
                        {imei}
                      </InertiaLink>
                    </td>
                    <td className="border-t">
                      <InertiaLink
                        tabIndex="-1"
                        href={route('contacts.edit', [idcontact, 1, read_contact])}
                        className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                      >
                        {datacriacontact}
                      </InertiaLink>
                    </td>
                    <td className="w-px border-t">
                      <InertiaLink
                        tabIndex="-1"
                        href={route('contacts.edit', [idcontact, 1, read_contact])}
                        className="flex items-center px-4"
                      >
                        {pct[pacote]}
                      </InertiaLink>
                    </td>
                    <td className="border-t">
                      <InertiaLink
                        tabIndex="-1"
                        href={route('contacts.edit', [idcontact, 1, read_contact])}
                        className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                      >
                        {currency(preco)}
                      </InertiaLink>
                    </td>
                    <td className="border-t">
                      <InertiaLink
                        tabIndex="-1"
                        href={route('contacts.edit', [idcontact, 1, read_contact])}
                        className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                      >
                        {datacriapagamento}
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
            {parceiros && parceiros.length === 0 && (
              <tr>
                <td className="px-6 py-4 border-t" colSpan="4">
                  Não foram encontrados parceiros.
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
