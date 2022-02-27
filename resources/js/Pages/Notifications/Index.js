import React from 'react';
import { InertiaLink, usePage, useForm } from '@inertiajs/inertia-react';
import Layout from '@/Shared/Layout';
import Icon from '@/Shared/Icon';
import Pagination from '@/Shared/Pagination';
import LoadingButton from '@/Shared/LoadingButton';

const Index = () => {
  const { contacts } = usePage().props;
  const {
    data,
    meta: { links }
  } = contacts;

  return (
    <div>
      <h1 className="mb-8 text-3xl font-bold">Notificações de registos ({data.length})</h1>
      <div className="overflow-x-auto bg-white rounded shadow">
        <table className="w-full whitespace-nowrap">
          <thead>
            <tr className="font-bold text-left">
              <ButtonQueryNotification type= "4" name = "Todas"/>
            </tr>
          </thead>
          <tbody>
            {data.map(
              ({ id, first_name, last_name, imei, codigo_equipa, read_contact, created_at }) => (
                <tr
                  key={id}
                  className={`hover:bg-gray-100 focus-within:bg-yellow-100 ${
                    read_contact == '0' ? 'bg-indigo-100' : 'bg-green-200'
                  }`}
                >
                  <td className="border-t">
                    <InertiaLink
                      href={route('contacts.edit', id)}
                      className="flex items-center px-6 py-4 focus:text-indigo-700 focus:outline-none"
                    >
                      <span className="font-bold">{first_name + ' ' + last_name}</span>&nbsp;registado pela equipa&nbsp;<span className="font-bold">YOGA {codigo_equipa}</span>&nbsp;IMEI:&nbsp;<span className="font-bold">{imei}</span>&nbsp;{created_at}
                    </InertiaLink>
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

const ButtonQueryNotification = ({type, name}) => {

  const { get, processing } = useForm({});

  function handleSubmit(type, e) {
    e.preventDefault();
    get(route('contacts.notification', type));
  }

  return (
      <th className="px-6 pt-5 pb-4">
          <form onSubmit={e => handleSubmit(type, e)}>
           <LoadingButton
             loading={processing}
             type="submit"
             className="ml-auto btn-indigo"
           >
             {name}
           </LoadingButton>
           </form> 
      </th>
  );
}

Index.layout = page => <Layout title="Notificações de registo" children={page} />;

export default Index;