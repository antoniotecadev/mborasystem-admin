import React, {useState} from 'react';
import Icon from '@/Shared/Icon';
import LoadingButton from '@/Shared/LoadingButton';
import { InertiaLink, useForm } from '@inertiajs/inertia-react';

export default ({id, local, name}) => {
const [menuOpened, setMenuOpened] = useState(false);
const [notificationOpened, setNotificationOpened] = useState(false);

const { put, processing } = useForm({});

  const marcarNotificacao = (e, id, tipo, local, name) => {
    e.preventDefault();
    put(route('contacts.notification.marcar', [id, tipo, local, name]));
  }
  return (
    <div className="relative">
        <div
          className="flex items-center cursor-pointer select-none group"
          onClick={() => setNotificationOpened(true)}>
          <Icon
            className="w-5 h-5 text-gray-800 fill-current group-hover:text-indigo-600 focus:text-indigo-600"
            name="menu"
          />
        </div>
        <div className={notificationOpened ? '' : 'hidden'}>
          <div className="absolute top-0 right-0 left-auto z-20 py-2 mt-8 text-sm whitespace-nowrap bg-white rounded shadow-xl">
            <p>Marcar como</p>
            <p>___________</p>
            <LoadingButton
             loading={processing}
             className={`ml-auto mr-auto btn-sucess block px-6 py-2 hover:bg-indigo-600 hover:text-white`}
             onClick={(e) => marcarNotificacao(e, id, 1, local, name)}
           >
             Lida
           </LoadingButton>
            <p>___________</p>
            <LoadingButton
             loading={processing}
             className={`ml-auto mr-auto btn-danger block px-6 py-2 hover:bg-indigo-600 hover:text-white`}
             onClick={(e) => marcarNotificacao(e, id, 0, local, name)}
           >
            Não lida
            </LoadingButton>
            <p>___________</p>
            <LoadingButton
             loading={processing}
             className={`ml-auto mr-auto btn-sucess block px-6 py-2 hover:bg-indigo-600 hover:text-white`}
             onClick={(e) => marcarNotificacao(e, id, 2, local, name)}
           >
            Atendido
            </LoadingButton>
            <p>___________</p>
            <LoadingButton
             loading={processing}
             className={`ml-auto mr-auto btn-danger block px-6 py-2 hover:bg-indigo-600 hover:text-white`}
             onClick={(e) => marcarNotificacao(e, id, 3, local, name)}
           >
            Não atendido
            </LoadingButton>
          </div>
          <div
            onClick={() => {
              setNotificationOpened(false);
            }}
            className="fixed inset-0 z-10 bg-black opacity-25"
          ></div>
        </div>
      </div>
  );
};
