import React, { useState, useEffect } from 'react';
import { InertiaLink, usePage, useForm } from '@inertiajs/inertia-react';
import Layout from '@/Shared/Layout';
import Icon from '@/Shared/Icon';
import Pagination from '@/Shared/Pagination';
import LoadingButton from '@/Shared/LoadingButton';
import MenuMarcar from '@/Shared/MenuMarcar';

var tipo = 4;
const Index = () => {

  const { get, processing } = useForm({});
  const { contacts, quantidade } = usePage().props;
  const {
    data,
    meta: { links }
  } = contacts;

  const handleSubmit = (e, type) => {
    e.preventDefault();
    get(route('contacts.notification', type));
    tipo = type;
  }

  return (
    <div>
      <h1 className="mb-8 text-3xl font-bold">Notificações de registos ({data.length} - {quantidade}) - {tipo == '0' ? 'Não lidas' : tipo == '1' ? 'Lidas' : tipo == '2' ? 'Não atendido' : tipo == '3' ? 'Atendido' : 'Todas' }</h1>
      <div className="flex flex-wrap">
        <ButtonQueryNotification handleSubmit = {handleSubmit} processing = {processing} type= "4" name = "Todas" color = "btn-indigo"/>
        <ButtonQueryNotification handleSubmit = {handleSubmit} processing = {processing} type= "0" name = "Não lidas" color = 'btn-danger'/>
        <ButtonQueryNotification handleSubmit = {handleSubmit} processing = {processing} type= "1" name = "Lidas" color = 'btn-sucess'/>
        <ButtonQueryNotification handleSubmit = {handleSubmit} processing = {processing} type= "2" name = "❌" color = 'btn-danger'/>
        <ButtonQueryNotification handleSubmit = {handleSubmit} processing = {processing} type= "3" name = "✔" color = 'btn-sucess'/>
      </div>
      <div className="overflow-x-auto bg-white rounded shadow">
        <table className="w-full whitespace-nowrap">
          <thead>
            <tr className="font-bold text-left">
              <th>
              </th>
            </tr>
          </thead>
          <tbody>
            {data.map(
              ({ id, first_name, last_name, estado, imei, codigo_equipa, read_contact, created_at }) => (
                <tr
                  key={id}
                  className={`hover:bg-gray-100 focus-within:bg-yellow-100 ${
                    read_contact == '0' ? 'bg-indigo-100' : ''
                  }`}
                >
                  <td className="border-t">
                    <InertiaLink
                      href={route('contacts.edit', [id, 1, read_contact])}
                      className="flex items-center px-6 py-4 focus:text-indigo-700 focus:outline-none"
                    >
                      <span className="font-bold">{first_name + ' ' + last_name}</span>&nbsp;registado pela equipa&nbsp;<span className="font-bold">YOGA {codigo_equipa}</span>&nbsp;IMEI:&nbsp;<span className="font-bold">{imei}</span>&nbsp;{created_at}
                    </InertiaLink>
                  </td>
                  <LoadingButton
                    className={`ml-2 mt-2 text-black ${ estado == '0' ? 'btn-danger' : 'btn-sucess' }`}
                  >
                    <MenuMarcar />
                  </LoadingButton>
                </tr>
              )
            )}
            {data.length === 0 && (
              <tr>
                <td className="px-6 py-4 border-t" colSpan="4">
                  Nenhum notificação encontrada.
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

const ButtonQueryNotification = ({handleSubmit, processing, type, name, color}) => {
  return (
      <th className="px-6 pt-5 pb-4">
          <form onSubmit={ e => handleSubmit(e, type)}>
           <LoadingButton
             loading={processing}
             type="submit"
             className={`ml-auto ${ color }`}
           >
             {name}
           </LoadingButton>
           </form> 
      </th>
  );
}

Index.layout = page => <Layout title="Notificações de registo" children={page} />;

export default Index;