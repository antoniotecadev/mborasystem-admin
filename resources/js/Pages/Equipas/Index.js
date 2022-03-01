import React from 'react';
import { InertiaLink, usePage, useForm } from '@inertiajs/inertia-react';
import Layout from '@/Shared/Layout';
import Icon from '@/Shared/Icon';
import Pagination from '@/Shared/Pagination';
import SearchFilter from '@/Shared/SearchFilter';
import LoadingButton from '@/Shared/LoadingButton';

const Index = () => {
  const { equipas, quantidade } = usePage().props;
  const { errors, put, processing } = useForm({});
  const {
    data,
    meta: { links }
  } = equipas;

  function handleSubmit(id, e) {
    e.preventDefault();
    put(route('equipas.estado', id));
  }

  return (
    <div>
      <h1 className="mb-8 text-3xl font-bold">Equipas ({data.length} - {quantidade})</h1>
      <div className="flex items-center justify-between mb-6">
        <SearchFilter />
        <InertiaLink
          className="btn-indigo focus:outline-none"
          href={route('equipas.create')}
        >
          <span>Criar</span>
          <span className="hidden md:inline"> Equipa</span>
        </InertiaLink>
      </div>
      <div className="overflow-x-auto bg-white rounded shadow">
        <table className="w-full whitespace-nowrap">
          <thead>
            <tr className="font-bold text-left">
              <th className="px-6 pt-5 pb-4">Código</th>
            </tr>
          </thead>
          <tbody>
            {data.map(
              ({ id, codigo, estado, deleted_at }) => (
                <tr
                  key={id}
                  className={`hover:bg-gray-100 focus-within:bg-yellow-100 ${
                    estado == '0' ? 'bg-red-100' : 'bg-green-200'
                  }`}
                >
                  <td className="border-t">
                    <InertiaLink
                      href={route('equipas.edit', id)}
                      className="flex items-center px-6 py-4 focus:text-indigo-700 focus:outline-none"
                    >
                      YOGA {codigo}
                      {deleted_at && (
                        <Icon
                          name="trash"
                          className="flex-shrink-0 w-3 h-3 ml-2 text-gray-400 fill-current"
                        />
                      )}
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
                  Nenhuma equipa encontrada.
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

Index.layout = page => <Layout title="Equipas" children={page} />;

export default Index;
