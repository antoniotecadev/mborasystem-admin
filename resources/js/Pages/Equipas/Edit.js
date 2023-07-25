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
import { toast } from 'react-toastify';
import { alertToast } from '@/Util/utilitario';

const Edit = () => {
  const [senha, setSenha] = useState(false);
  const { equipa, empresas, valorcada, valortotal, valortotalbruto, iniciodata, fimdata, numeroagente, percentagemtaxa, quantidade } = usePage().props;
  const [inicio, setInicio] = useState(iniciodata);
  const [fim, setFim] = useState(fimdata);
  const [numeroAgente, setNumeroAgente] = useState(2);
  const [percentagemTaxa, setPercentagemTaxa] = useState(30);
  const { data, setData, errors, put, post, processing } = useForm({
    codigo: equipa.data.codigo || '',
    estado: equipa.data.estado || '',
    password: '',
    created_at: equipa.data.created_at || ''
  });

  function handleSubmit(e) {
    e.preventDefault();
    if (equipa.data.deleted_at) {
      alertToast("⚠ Equipa eliminada não pode ser actualizada.", "update_equipa");
    } else {
      put(route('equipas.update', equipa.data.id));
    }
  }
  function handleSubmitPassword(e) {
    e.preventDefault();
    if (equipa.data.deleted_at) {
      alertToast("⚠ Password de Equipa eliminada não pode ser actualizada.", "update_equipa");
    } else {
      put(route('password.update', equipa.data.id));
    }
  }

  function destroy() {
    if (confirm('Você tem certeza que deseja eliminar esta equipa?')) {
      var motivo = prompt('Qual é o motivo de sua eliminação?');
      if (motivo) {
        if (motivo.length > 150) {
          alertToast("⚠ Só é permitido 150 caracteres", "max_caractere");
        } else {
          Inertia.delete(route('equipas.destroy', [equipa.data.id, motivo]));
        }
      }
    }
  }

  function restore() {
    if (confirm('Tem certeza que deseja restaurar essa equipa?')) {
      Inertia.put(route('equipas.restore', equipa.data.id));
    }
  }

  function calcularRendimento(e) {
    e.preventDefault();
    if (isUndefined(inicio)) {
      toast.warning('Data de início não definada', {
        toastId: 0
      });
    } else if (isUndefined(fim)) {
      toast.warning('Data de fim não definada', {
        toastId: 1
      });
    } else {
      Inertia.get(route('equipas.calcular', [equipa.data.id, equipa.data.codigo, inicio, fim, numeroAgente, percentagemTaxa]));
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
        <span
          className={`${data.estado == '0' ? 'text-red-400' : 'text-green-400'
            }`}
        >
          {data.estado == '0' ? 'Desactivo' : 'Activo'}
        </span>
      </h1>
      {equipa.data.deleted_at && (
        <TrashedMessage onRestore={restore}>
          <p>Esta equipa foi eliminada.{' '}<DeleteButton onDelete={e => alertToast(equipa.data.motivo_elimina, "equipa_motivo_elimina")}>Motivo</DeleteButton></p>
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
              label="Criada"
              type="text"
              value={data.created_at}
              readOnly
            />
          </div>
          <div className="flex items-center px-8 py-4 bg-gray-100 border-t border-gray-200">
            {!equipa.data.deleted_at && (
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
        <form onSubmit={handleSubmitPassword}>
          <div className="flex flex-wrap p-8 -mb-8 -mr-6">
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
            <div className="flex items-center justify-end mb-2">
              <LoadingButton
                loading={processing}
                type="submit"
                className="ml-auto btn-indigo"
              >
                Alterar palavra passe
              </LoadingButton>
            </div>
          </div>
        </form>
      </div>
      <h2 className="mt-12 text-2xl font-bold">Agente(s)</h2>
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
            {equipa.data.agentes.map(
              ({ id, nome_completo, telefone, email, estado, deleted_at }) => {
                return (
                  <tr
                    key={id}
                    className={`hover:bg-gray-100 focus-within:bg-gray-100 ${estado == '0' ? 'bg-red-100' : 'bg-green-200'
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
            {equipa.data.agentes.length === 0 && (
              <tr>
                <td className="px-6 py-4 border-t" colSpan="4">
                  Não foram encontrados agentes.
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>
      <h2 className="mt-12 text-2xl font-bold">Rendimento ({iniciodata == undefined ? "" : iniciodata + ' - ' + fimdata == undefined ? "" : fimdata})</h2>
      <div className="mt-6 max-w-3xl overflow-hidden bg-white rounded shadow">
        <form onSubmit={calcularRendimento}>
          <div className="flex flex-wrap p-8 -mb-8 -mr-6">
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label={iniciodata == undefined ? "De" : "De: " + iniciodata}
              name="inicio"
              type="date"
              value={inicio}
              onChange={e => setInicio(e.target.value)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label={fimdata == undefined ? "Até" : "Até: " + fimdata}
              name="fim"
              type="date"
              value={fim}
              onChange={e => setFim(e.target.value)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label={numeroagente == undefined ? "Para" : "Para: " + numeroagente + " Agente(s)"}
              name="numero_agente"
              type="number"
              value={numeroAgente}
              onChange={e => setNumeroAgente(e.target.value)}
              min={1}
              max={4}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label={percentagemtaxa == undefined ? "Percentagem" : "Percentagem: " + percentagemtaxa + " %"}
              name="percentagem"
              type="number"
              value={percentagemTaxa}
              onChange={e => setPercentagemTaxa(e.target.value)}
              min={1}
              max={100}
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
                  {percentagemTaxa}%
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
      <h2 className="mt-12 text-2xl font-bold">Empresas ({quantidade})</h2>
      <div className="mt-6 overflow-x-auto bg-white rounded shadow">
        <table className="w-full whitespace-nowrap">
          <thead>
            <tr className="font-bold text-left">
              <th className="px-6 pt-5 pb-4">Empresa</th>
              <th className="px-6 pt-5 pb-4">IMEI</th>
              <th className="px-6 pt-5 pb-4">Data (Criação)</th>
              <th className="px-6 pt-5 pb-4">Pacote</th>
              <th className="px-6 pt-5 pb-4">Preço</th>
              <th className="px-6 pt-5 pb-4">Data (Pagamento)</th>

            </tr>
          </thead>
          <tbody>
            {empresas && empresas.map(
              ({ idcontact, empresa, imei, read_contact, datacriacontact, pacote, preco, datacriapagamento }) => {
                return (
                  <tr
                    key={idcontact}
                    className='hover:bg-gray-100 focus-within:bg-gray-100 bg-yellow-200'>
                    <td className="border-t">
                      <InertiaLink
                        href={route('contacts.edit', [idcontact, 1, read_contact])}
                        className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                      >
                        {empresa}
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
            {empresas && empresas.length === 0 && (
              <tr>
                <td className="px-6 py-4 border-t" colSpan="4">
                  Não foram encontradas empresas.
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
