import React, { useEffect } from 'react';
import { InertiaLink, usePage, useForm } from '@inertiajs/inertia-react';
import Layout from '@/Shared/Layout';
import LoadingButton from '@/Shared/LoadingButton';
import TextInput from '@/Shared/TextInput';
import SelectInput from '@/Shared/SelectInput';
import Icon from '@/Shared/Icon';
import { Inertia } from '@inertiajs/inertia';

const Create = () => {

  const { equipas, municipios, bairros } = usePage().props;
  const { data, setData, errors, post, processing } = useForm({
    first_name: '',
    last_name: '',
    nif_bi: '',
    email: '',
    phone: '',
    alternative_phone: '',
    empresa: '',
    municipality: '',
    district: '',
    street: '',
    estado: '0',
    imei: '',
    codigo_equipa: ''
  });

  function handleSubmit(e) {
    e.preventDefault();
      post(route('contacts.store'));
  }

  function gerarNumeroAleatorio(e) {
    e.preventDefault();
    let imei1 = Math.floor(Math.random() * (9999999 - 1000000)) + 1000000;
    let imei2 = Math.floor(Math.random() * (9999999 - 1000000)) + 1000000;
    setData('imei', imei1 + '' + imei2);
  }

  const getBairros = (municipio) => {
    setData('municipality', municipio)
    Inertia.get(route('contacts.bairros', municipio), {}, { preserveState: true });
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
              label="Empresa"
              name="empresa"
              errors={errors.empresa}
              value={data.empresa}
              onChange={e => setData('empresa', e.target.value)}
            />
            <SelectInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Município"
              name="municipality"
              errors={errors.municipality}
              value={data.municipality}
              onClick={e => setData('district', "")}
              onChange={e => getBairros(e.target.value)}
            >
              <option value=""></option>
              {municipios != undefined && municipios.map(({ id, nome }) => (
                <option key={id} value={nome}>
                  {nome}
                </option>
              ))}
            </SelectInput>
            <SelectInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Bairro"
              name="district"
              errors={errors.district}
              value={data.district}
              onChange={e => setData('district', e.target.value)}>
              <option value=""></option>
              {(bairros != undefined && municipios != undefined) && bairros.map(({ id, nome }) => (
                <option key={id} value={nome}>
                  {nome}
                </option>
              ))}
            </SelectInput>
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Rua"
              name="street"
              type="text"
              errors={errors.street}
              value={data.street}
              onChange={e => setData('street', e.target.value)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="IMEI"
              name="imei"
              type="text"
              errors={errors.imei}
              value={data.imei}
              onChange={e => setData('imei', e.target.value)}
              readOnly
            />
            <div className="flex items-center justify-end mb-4">
              <LoadingButton
                loading={processing}
                onClick={gerarNumeroAleatorio}
                className="btn-indigo"
              >
                <Icon name="actualizar" />
              </LoadingButton>
            </div>
            <SelectInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Equipa"
              name="codigo_equipa"
              errors={errors.codigo_equipa}
              value={data.codigo_equipa}
              onChange={e => setData('codigo_equipa', e.target.value)}
            >
              <option value=""></option>
              {equipas.map(({ id, codigo }) => (
                <option key={id} value={codigo}>
                  {codigo}
                </option>
              ))}
            </SelectInput>
            <SelectInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Estado"
              name="estado"
              errors={errors.estado}
              value={data.estado}
              onChange={e => setData('estado', e.target.value)}
            >
              <option value="1">ACTIVO</option>
              <option value="0">DESACTIVO</option>
            </SelectInput>
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
