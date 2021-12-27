import React from 'react';
import { InertiaLink, usePage, useForm } from '@inertiajs/inertia-react';
import Layout from '@/Shared/Layout';
import Icon from '@/Shared/Icon';
import Pagination from '@/Shared/Pagination';
import SearchFilter from '@/Shared/SearchFilter';
import LoadingButton from '@/Shared/LoadingButton';

const Index = () => {
  const { contacts } = usePage().props;
  const { errors, put, processing } = useForm({});
  const {
    data,
    meta: { links }
  } = contacts;

  function handleSubmit(id, e) {
    e.preventDefault();
    put(route('contacts.estado', id));
  }

  return (
    <div>
      <h1 className="mb-8 text-3xl font-bold">Parceiros ({data.length})</h1>
      <div className="flex items-center justify-between mb-6">
        <SearchFilter />
        <InertiaLink
          className="btn-indigo focus:outline-none"
          href={route('contacts.create')}
        >
          <span>Criar</span>
          <span className="hidden md:inline"> Parceiros</span>
        </InertiaLink>
      </div>
      <div className="overflow-x-auto bg-white rounded shadow">
        <table className="w-full whitespace-nowrap">
          <thead>
            <tr className="font-bold text-left">
              <th className="px-6 pt-5 pb-4">Nome</th>
              <th className="px-6 pt-5 pb-4">Cantina</th>
              <th className="px-6 pt-5 pb-4">Email</th>
              <th className="px-6 pt-5 pb-4" colSpan="2">
                Telefone
              </th>
              <th>Operação</th>
            </tr>
          </thead>
          <tbody>
            {data.map(
              ({ id, name, cantina, email, phone, estado, deleted_at }) => (
                <tr
                  key={id}
                  className={`hover:bg-gray-100 focus-within:bg-yellow-100 ${
                    estado == '0' ? 'bg-red-100' : 'bg-green-200'
                  }`}
                >
                  <td className="border-t">
                    <InertiaLink
                      href={route('contacts.edit', id)}
                      className="flex items-center px-6 py-4 focus:text-indigo-700 focus:outline-none"
                    >
                      {name}
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
                      href={route('contacts.edit', id)}
                    >
                      {/* {organization ? organization.name : ''} */}
                      {cantina}
                    </InertiaLink>
                  </td>
                  <td className="border-t">
                    <InertiaLink
                      tabIndex="-1"
                      href={route('contacts.edit', id)}
                      className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                    >
                      {email}
                    </InertiaLink>
                  </td>
                  <td className="border-t">
                    <InertiaLink
                      tabIndex="-1"
                      href={route('contacts.edit', id)}
                      className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                    >
                      {phone}
                    </InertiaLink>
                  </td>
                  <td className="w-px border-t">
                    <InertiaLink
                      tabIndex="-1"
                      href={route('contacts.edit', id)}
                      className="flex items-center px-4 focus:outline-none"
                    >
                      <Icon
                        name="cheveron-right"
                        className="block w-6 h-6 text-gray-400 fill-current"
                      />
                    </InertiaLink>
                  </td>
                  <td>
                    <form onSubmit={e => handleSubmit(id, e)}>
                      <LoadingButton
                        loading={processing}
                        type="submit"
                        className={`ml-auto ${
                          estado == '0' ? 'btn-sucess' : 'btn-danger'
                        }`}
                      >
                        {estado == '0' ? 'Activar' : 'Desactivar'}
                      </LoadingButton>
                    </form>
                  </td>
                </tr>
              )
            )}
            {data.length === 0 && (
              <tr>
                <td className="px-6 py-4 border-t" colSpan="4">
                  Nenhum parceiro encontrado.
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

Index.layout = page => <Layout title="Parceiros" children={page} />;

export default Index;
