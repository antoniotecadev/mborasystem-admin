import React from 'react';
import { Inertia } from '@inertiajs/inertia';
import { InertiaLink, useForm, usePage } from '@inertiajs/inertia-react';
import Layout from '@/Shared/Layout';
import LoadingButton from '@/Shared/LoadingButton';
import TextInput from '@/Shared/TextInput';
import SelectInput from '@/Shared/SelectInput';

const Create = () => {
  const { contacts } = usePage().props;
  const { data, setData, errors, post, processing } = useForm({
    pacote: '',
    inicio: '',
    fim: '',
    nome: '',
    contact_id: ''
  });

  function handleSubmit(e) {
    e.preventDefault();
    post(route('pagamentos.store'));
  }

  return (
    <div>
      <h1 className="mb-8 text-3xl font-bold">
        <InertiaLink
          href={route('pagamentos')}
          className="text-indigo-600 hover:text-indigo-700"
        >
          Pagamentos
        </InertiaLink>
        <span className="font-medium text-indigo-600"> /</span> Criar
      </h1>
      <div className="max-w-3xl overflow-hidden bg-white rounded shadow">
        <form onSubmit={handleSubmit}>
          <div className="flex flex-wrap p-8 -mb-8 -mr-6">
          <SelectInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Parceiro"
              name="contact_id"
              errors={errors.contact_id}
              value={data.contact_id}
              onChange={e => setData('contact_id', e.target.value)}>
             <option value=""></option>
              {contacts.map(({ id, first_name, last_name, cantina, phone }) => (
                <option key={id} value={id}>
                  {first_name} {last_name} - {cantina} - {phone}
                </option>
              ))}
            </SelectInput>
            <SelectInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Pacote"
              name="pacote"
              errors={errors.pacote}
              value={data.pacote}
              onChange={e => setData('pacote', e.target.value)}>
              <option value=""></option>
              <option value="0">ALUMÍNIO</option>
              <option value="1">BRONZE</option>
              <option value="2">OURO</option>
            </SelectInput>
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Início"
              name="inicio"
              type="date"
              errors={errors.inicio}
              value={data.inicio}
              onChange={e => setData('inicio', e.target.value)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Fim"
              name="fim"
              type="date"
              errors={errors.fim}
              value={data.fim}
              onChange={e => setData('fim', e.target.value)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Bairro"
              name="district"
              type="time"
              errors={errors.district}
              value={data.district}
              onChange={e => setData('district', e.target.value)}
            />
          </div>
          <div className="flex items-center justify-end px-8 py-4 bg-gray-100 border-t border-gray-200">
            <LoadingButton
              loading={processing}
              type="submit"
              className="btn-indigo"
            >
              Efectuar pagamento
            </LoadingButton>
          </div>
        </form>
      </div>
    </div>
  );
};

Create.layout = page => <Layout title="Efectuar pagamento" children={page} />;

export default Create;
