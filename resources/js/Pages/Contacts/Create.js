import React from 'react';
import { Inertia } from '@inertiajs/inertia';
import { InertiaLink, usePage, useForm } from '@inertiajs/inertia-react';
import Layout from '@/Shared/Layout';
import LoadingButton from '@/Shared/LoadingButton';
import TextInput from '@/Shared/TextInput';
import SelectInput from '@/Shared/SelectInput';

const Create = () => {
  // const { organizations } = usePage().props;
  const { data, setData, errors, post, processing } = useForm({
    first_name: '',
    last_name: '',
    nif_bi: '',
    // organization_id: '',
    email: '',
    phone: '',
    alternative_phone: '',
    cantina: '',
    municipality: '',
    district: '',
    street: ''
  });

  function handleSubmit(e) {
    e.preventDefault();
    post(route('contacts.store'));
  }

  return (
    <div>
      <h1 className="mb-8 text-3xl font-bold">
        <InertiaLink
          href={route('contacts')}
          className="text-indigo-600 hover:text-indigo-700"
        >
          Parceiros
        </InertiaLink>
        <span className="font-medium text-indigo-600"> /</span> Criar
      </h1>
      <div className="max-w-3xl overflow-hidden bg-white rounded shadow">
        <form onSubmit={handleSubmit}>
          <div className="flex flex-wrap p-8 -mb-8 -mr-6">
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Nome"
              name="first_name"
              errors={errors.first_name}
              value={data.first_name}
              onChange={e => setData('first_name', e.target.value)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Sobrenome"
              name="last_name"
              errors={errors.last_name}
              value={data.last_name}
              onChange={e => setData('last_name', e.target.value)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="NIF/BI"
              name="nif_bi"
              type="text"
              errors={errors.nif_bi}
              value={data.nif_bi}
              onChange={e => setData('nif_bi', e.target.value)}
            />
            {/* <SelectInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Cantina"
              name="organization_id"
              errors={errors.organization_id}
              value={data.organization_id}
              onChange={e => setData('organization_id', e.target.value)}
            >
              <option value=""></option>
              {organizations.map(({ id, name }) => (
                <option key={id} value={id}>
                  {name}
                </option>
              ))}
            </SelectInput> */}
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Email"
              name="email"
              type="email"
              errors={errors.email}
              value={data.email}
              onChange={e => setData('email', e.target.value)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Telefone"
              name="phone"
              type="text"
              errors={errors.phone}
              value={data.phone}
              onChange={e => setData('phone', e.target.value)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Telefone alternativo"
              name="alternative_phone"
              type="text"
              errors={errors.alternative_phone}
              value={data.alternative_phone}
              onChange={e => setData('alternative_phone', e.target.value)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Cantina"
              name="cantina"
              errors={errors.cantina}
              value={data.cantina}
              onChange={e => setData('cantina', e.target.value)}
            />
            <SelectInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Município"
              name="municipality"
              errors={errors.municipality}
              value={data.municipality}
              onChange={e => setData('municipality', e.target.value)}
            >
              <option value=""></option>
              <option value="Luanda">LUANDA</option>
              <option value="Belas">BELAS</option>
              <option value="Cazenga">CAZENGA</option>
              <option value="Cacuaco">CACUACO</option>
              <option value="Viana">VIANA</option>
              <option value="Icolo e Bengo">ICOLO E BENGO</option>
              <option value="Quissama">QUISSAMA</option>
              <option value="Talatona">TALATONA</option>
              <option value="Quilamba Quiaxi">QUILAMBA QUIAXI</option>
            </SelectInput>
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Bairro"
              name="district"
              type="text"
              errors={errors.district}
              value={data.district}
              onChange={e => setData('district', e.target.value)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Rua"
              name="street"
              type="text"
              errors={errors.street}
              value={data.street}
              onChange={e => setData('street', e.target.value)}
            />
          </div>
          <div className="flex items-center justify-end px-8 py-4 bg-gray-100 border-t border-gray-200">
            <LoadingButton
              loading={processing}
              type="submit"
              className="btn-indigo"
            >
              Criar parceiro
            </LoadingButton>
          </div>
        </form>
      </div>
    </div>
  );
};

Create.layout = page => <Layout title="Criar parceiro" children={page} />;

export default Create;
