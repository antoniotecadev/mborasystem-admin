import React from 'react';
import { InertiaLink, usePage, useForm } from '@inertiajs/inertia-react';
import Layout from '@/Shared/Layout';
import Pagination from '@/Shared/Pagination';
import LoadingButton from '@/Shared/LoadingButton';
import MenuMarcar from '@/Shared/MenuMarcar';
import { toast } from 'react-toastify';
import firebase from '@/firebase';
import { ref, update } from "firebase/database";
import { numeroNotificacao } from '@/Util/utilitario';

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

  const abrirNotificacao = (id, type, read_contact, imei, first_name, last_name, codigo_equipa, created_at) => {
    location.href = route('contacts.edit', [id, type, read_contact]);
    const visualizadoData = {
      id: id,
      imei: imei,
      nome: first_name,
      sobrenome: last_name,
      codigoEquipa: codigo_equipa,
      data_cria: created_at,
      visualizado: true
    };
    if (read_contact == "0") {
      const updates = {};
      updates['/cliente/' + imei + '/'] = visualizadoData;
      update(ref(firebase), updates)
        .then(() => {
          toast.info(first_name + " marcado como lido no firebase");
        })
        .catch(error => {
          toast.error(first_name + " não marcado como lido no firebase: " + error.message);
        });
      localStorage.setItem("notificacao_registo", numeroNotificacao());
    }
  }

  return (
    <div>
      <h1 className="mb-8 text-3xl font-bold">Notificações de registos ({data.length} - {quantidade}) - {tipo == '0' ? 'Não lidas' : tipo == '1' ? 'Lidas' : tipo == '3' ? 'Não atendido' : tipo == '2' ? 'Atendido' : 'Todas'}</h1>
      <div className="flex flex-wrap">
        <ButtonQueryNotification handleSubmit={handleSubmit} processing={processing} type="4" name="Todas" color="btn-indigo" />
        <ButtonQueryNotification handleSubmit={handleSubmit} processing={processing} type="0" name="Não lidas" color='btn-indigo' />
        <ButtonQueryNotification handleSubmit={handleSubmit} processing={processing} type="1" name="Lidas" color='btn-indigo' />
        <ButtonQueryNotification handleSubmit={handleSubmit} processing={processing} type="3" name="❌" color='btn-danger' />
        <ButtonQueryNotification handleSubmit={handleSubmit} processing={processing} type="2" name="✔" color='btn-sucess' />
      </div>
      <div className="overflow-x-auto bg-white rounded shadow">
        <table className="w-full whitespace-nowrap">
          <thead>
            <tr className="font-bold">
              <th>
              </th>
            </tr>
          </thead>
          <tbody>
            {data.map(
              ({ id, first_name, last_name, estado, imei, codigo_equipa, read_contact, created_at }) => (
                <tr
                  key={id}
                  className={`hover:bg-gray-100 focus-within:bg-yellow-100 ${read_contact == '0' ? 'bg-indigo-100' : ''
                    }`}
                >
                  <td className="border-t">
                    <InertiaLink
                      onClick={() => abrirNotificacao(id, 1, read_contact, imei, first_name, last_name, codigo_equipa, created_at)}
                      className="flex items-center px-6 py-4 focus:text-indigo-700 focus:outline-none"
                    >
                      <span className="font-bold">{first_name + ' ' + last_name}</span>&nbsp;registado pela equipa&nbsp;<span className="font-bold">YOGA {codigo_equipa}</span>&nbsp;IMEI:&nbsp;<span className="font-bold">{imei}</span>&nbsp;{created_at}
                    </InertiaLink>
                  </td>
                  <LoadingButton
                    className={`ml-2 mt-2 text-black ${estado == '0' ? 'btn-danger' : 'btn-sucess'}`}
                  >
                    <MenuMarcar id={id} local={tipo} name={first_name + ' ' + last_name} />
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

const ButtonQueryNotification = ({ handleSubmit, processing, type, name, color }) => {
  return (
    <th className="px-6 pt-5 pb-4">
      <form onSubmit={e => handleSubmit(e, type)}>
        <LoadingButton
          loading={processing}
          type="submit"
          className={`ml-auto ${color}`}
        >
          {name}
        </LoadingButton>
      </form>
    </th>
  );
}

Index.layout = page => <Layout title="Notificações de registo" children={page} />;

export default Index;
