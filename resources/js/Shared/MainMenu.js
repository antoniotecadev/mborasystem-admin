import React from 'react';
import MainMenuItem from '@/Shared/MainMenuItem';
import MainMenuItemNotification from '@/Shared/MainMenuItemNotification';

export default ({ className }) => {
  return (
    <div className={className}>
      <MainMenuItem text="Dashboard" link="dashboard" icon="dashboard" />
      <MainMenuItemNotification />
      <MainMenuItem text="Parceiros" link="contacts" icon="users" />
      <MainMenuItem text="Pagamentos" link="pagamentos" icon="pagamento" />
      <MainMenuItem text="Equipas" link="equipas" icon="equipa" />
      <MainMenuItem text="Agentes" link="agentes" icon="agente" />
      <MainMenuItem text="Reports" link="reports" icon="printer" />
    </div>
  );
};
