import React from 'react';
import { InertiaLink, usePage } from '@inertiajs/inertia-react';
import Layout from '@/Shared/Layout';
import Icon from '@/Shared/Icon';
import Pagination from '@/Shared/Pagination';
import SearchFilter from '@/Shared/SearchFilter';

const Index = () => {
  const { pagamentos } = usePage().props;
  const {
    data,
    meta: { links }
  } = pagamentos;

  var hoje = new Date();

  var dataActual = hoje.getFullYear() + '-' + String(hoje.getMonth() + 1).padStart(2, '0') + '-' + ((hoje.getDate() < '10' ? '0' : '') + hoje.getDate());

  var amanha = new Date(hoje.getTime());
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
      <h1 className="mb-8 text-3xl font-bold">Pagamentos ({data.length})</h1>
      <div className="flex items-center justify-between mb-6">
        <SearchFilter />
        <InertiaLink
          className="btn-indigo focus:outline-none"
          href={route('pagamentos.create')}
        >
          <span>Efectuar</span>
          <span className="hidden md:inline"> Pagamento</span>
        </InertiaLink>
      </div>
      <div className="overflow-x-auto bg-white rounded shadow">
        <table className="w-full whitespace-nowrap">
          <thead>
            <tr className="font-bold text-left">
              <th className="px-6 pt-5 pb-4">Nome parceiro</th>
              <th className="px-6 pt-5 pb-4">Cantina</th>
              <th className="px-6 pt-5 pb-4">Início</th>
              <th className="px-6 pt-5 pb-4">Fim{dataActual}</th>
            </tr>
          </thead>
          <tbody>
            {data.map(({ id, inicio, fim, deleted_at, contact }) => (
              <tr
                key={id}
                className={`hover:bg-gray-100 focus-within:bg-gray-100 ${
                  Date.parse(fim) <= Date.parse(dataActual) ? 'bg-red-100' : Date.parse(fim) == Date.parse(dataAmanha)?'bg-yellow-400':'bg-green-200'
                }`}
              >
                <td className="border-t">
                  <InertiaLink
                    href={route('pagamentos.edit', id)}
                    className="flex items-center px-6 py-4 focus:text-indigo-700 focus:outline-none"
                  >
                    {contact
                      ? contact.first_name + ' ' + contact.last_name
                      : ''}{' '}
                    {Date.parse(fim) <= Date.parse(dataActual) ? ' (Terminado) ' : ''} {Date.parse(fim) == Date.parse(dataAmanha) ? ' (Termina amanhã) ' : ''}
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
                    tabIndex="1"
                    className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                    href={route('pagamentos.edit', id)}
                  >
                    {contact ? contact.cantina : ''}
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
                    className="flex items-center px-4 focus:outline-none"
                  >
                    <Icon
                      name="cheveron-right"
                      className="block w-6 h-6 text-gray-400 fill-current"
                    />
                  </InertiaLink>
                </td>
              </tr>
            ))}
            {data.length === 0 && (
              <tr>
                <td className="px-6 py-4 border-t" colSpan="4">
                  Nenhum pagamento encontrado.
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>
      <Pagination links={links} />
    </div>
  );
};

Index.layout = page => <Layout title="Pagamentos" children={page} />;

export default Index;
