import React from 'react';
import { InertiaLink, usePage } from '@inertiajs/inertia-react';
import Layout from '@/Shared/Layout';
import Icon from '@/Shared/Icon';
import Pagination from '@/Shared/Pagination';
import SearchFilter from '@/Shared/SearchFilter';

const Index = () => {
  const { agentes, quantidade } = usePage().props;
  const {
    data,
    meta: { links }
  } = agentes;

  return (
    <div>
      <h1 className="mb-8 text-3xl font-bold">Agentes ({data.length} - {quantidade})</h1>
      <div className="flex items-center justify-between mb-6">
        <SearchFilter placeHolder = "nome, bi, telefone, município, equipa"/>
        <InertiaLink
          className="btn-indigo focus:outline-none"
          href={route('agentes.create')}
        >
          <span>Criar</span>
          <span className="hidden md:inline"> Agente</span>
        </InertiaLink>
      </div>
      <div className="overflow-x-auto bg-white rounded shadow">
        <table className="w-full whitespace-nowrap">
          <thead>
            <tr className="font-bold text-left">
              <th className="px-6 pt-5 pb-4">Equipa</th>
              <th className="px-6 pt-5 pb-4">Agente</th>
              <th className="px-6 pt-5 pb-4">B.I</th>
              <th className="px-6 pt-5 pb-4">Telefone</th>
              <th className="px-6 pt-5 pb-4"></th>
            </tr>
          </thead>
          <tbody>
            {data.map(({ id, nome_completo, bi, telefone, estado, deleted_at, equipa }) => (
              <tr
                key={id}
                className={`hover:bg-gray-100 focus-within:bg-gray-100 ${
                  estado == '0' ? 'bg-red-100' : 'bg-green-200'
                }`}
              >
                <td className="border-t">
                  <InertiaLink
                    href={route('agentes.edit', id)}
                    className="flex items-center px-6 py-4 focus:text-indigo-700 focus:outline-none"
                  >
                    YOGA {equipa ? equipa.codigo : ''}
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
                    {nome_completo}{' '}
                    {estado == '0' ? ' (Desactivo) ' : ''}
                  </InertiaLink>
                </td>
                <td className="border-t">
                  <InertiaLink
                    tabIndex="-1"
                    href={route('agentes.edit', id)}
                    className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                  >
                    {bi}
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
                <td className="w-px border-t">
                  <InertiaLink
                    tabIndex="-1"
                    href={route('agentes.edit', id)}
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
                  Nenhum agente encontrado.
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

Index.layout = page => <Layout title="Agentes" children={page} />;

export default Index;
