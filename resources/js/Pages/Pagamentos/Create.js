import React, { useState } from 'react';
import { InertiaLink, useForm, usePage } from '@inertiajs/inertia-react';
import Layout from '@/Shared/Layout';
import LoadingButton from '@/Shared/LoadingButton';
import TextInput from '@/Shared/TextInput';
import SelectInput from '@/Shared/SelectInput';
import Pagination from '@/Shared/Pagination';
import SearchFilter from '@/Shared/SearchFilter';
import Icon from '@/Shared/Icon';
import {
  getDataFimTrimestralSemestralAnual,
  tipoPacote,
  currency
} from '@/Util/utilitario';

const Create = () => {
  const [parceiro, setParceiro] = useState('');
  const [datafinal, setDataFinal] = useState();
  const { data, setData, errors, post, processing } = useForm({
    pacote: '',
    tipo_pagamento: '',
    preco: '',
    inicio: '',
    fim: '',
    nome: '',
    contact_id: '',
    pagamento: ''
  });

  function diasNoMes(mes, ano) {
    var data = new Date(ano, mes, 0);
    return parseInt(data.getDate());
  }

  const getDataFinal = e => {
    let diaFinal, dataFinal;
    let data = e.target.value;

    const diaT = parseInt(data.split('-', 3)[2]);
    const mesT = parseInt(data.split('-', 3)[1]);
    const anoT = parseInt(data.split('-', 3)[0]);

    const mes = (mesT == 12 ? '' : mesT) + 1;
    const anoFinal = mesT == 12 ? anoT + 1 : anoT;
    const mesFinal = (mes < '10' ? '0' : '') + mes;
    const dia = 30 + diaT - diasNoMes(mesT, anoT);

    if (dia > diasNoMes(mesFinal, anoFinal)) {
      diaFinal = dia - diasNoMes(mesFinal, anoFinal);
      dataFinal =
        anoFinal +
        '-' +
        ((parseInt(mesFinal) + 1 < '10' ? '0' : '') +
          (parseInt(mesFinal) + 1)) +
        '-' +
        ((diaFinal < '10' ? '0' : '') + diaFinal);
    } else {
      diaFinal =
        (dia < '10' ? '0' : '') + dia == '00'
          ? '31'
          : (dia < '10' ? '0' : '') + dia;

      if (diaT == 1 && diasNoMes(mesT, anoT) == 31) {
        dataFinal =
          anoFinal +
          '-' +
          ((parseInt(mesFinal) - 1 < '10' ? '0' : '') +
            (parseInt(mesFinal) - 1)) +
          '-' +
          diaFinal;
      } else {
        dataFinal = anoFinal + '-' + mesFinal + '-' + diaFinal;
      }
    }
    setData('inicio', e.target.value);
    setDataFinal(dataFinal);
  };

  function handleSubmit(e) {
    e.preventDefault();
    post(route('pagamentos.store'));
  }

  const precopacote = tipoPacote(
    Number(data.pacote),
    Number(data.tipo_pagamento)
  );

  function getParceiro(e, id, name, cantina, phone) {
    e.preventDefault();
    setData('contact_id', id);
    setParceiro(name + ' - ' + cantina + ' - ' + phone);
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
      <div className="overflow-x-auto bg-white rounded shadow">
        <table className="w-full whitespace-nowrap">
          <thead>
            <tr className="font-bold text-left">
              <th className="px-6 pt-5 pb-4">Pacote/Tipo</th>
              <th className="px-6 pt-5 pb-4">Bronze</th>
              <th className="px-6 pt-5 pb-4">Alumínio</th>
              <th className="px-6 pt-5 pb-4">Ouro</th>
            </tr>
          </thead>
          <tbody>
            <tr key={0} className="hover:bg-gray-100 focus-within:bg-gray-100">
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none font-bold">MENSAL</p></td>
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">{currency(tipoPacote(0, 1))}</p></td>
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">{currency(tipoPacote(1, 1))}</p></td>
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">{currency(tipoPacote(2, 1))}</p></td>
            </tr>
            <tr key={1} className="hover:bg-gray-100 focus-within:bg-gray-100">
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none font-bold">TRIMESTRAL</p></td>
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">{currency(tipoPacote(0, 3))}</p></td>
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">{currency(tipoPacote(1, 3))}</p></td>
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">{currency(tipoPacote(2, 3))}</p></td>
            </tr>
            <tr key={2} className="hover:bg-gray-100 focus-within:bg-gray-100">
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none font-bold">SEMESTRAL</p></td>
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">{currency(tipoPacote(0, 6))}</p></td>
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">{currency(tipoPacote(1, 6))}</p></td>
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">{currency(tipoPacote(2, 6))}</p></td>
            </tr>
            <tr key={3} className="hover:bg-gray-100 focus-within:bg-gray-100">
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none font-bold">ANUAL</p></td>
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">{currency(tipoPacote(0, 12))}</p></td>
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">{currency(tipoPacote(1, 12))}</p></td>
              <td className="border-t"><p className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">{currency(tipoPacote(2, 12))}</p></td>
            </tr>
          </tbody>
        </table>
      </div>
      <br />
      <ListaParceiros getParceiro={getParceiro} />
      <div className="max-w-3xl overflow-hidden bg-white rounded shadow">
        <form onSubmit={handleSubmit}>
          <div className="flex flex-wrap p-8 -mb-8 -mr-6">
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label={"Parceiro:"}
              type="text"
              errors={errors.contact_id}
              value={parceiro}
              readOnly
            />
            <SelectInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Pacote"
              name="pacote"
              errors={errors.pacote}
              value={data.pacote}
              onChange={e => setData('pacote', e.target.value)}
            >
              <option value=""></option>
              <option value="0">BRONZE</option>
              <option value="1">ALUMÍNIO</option>
              <option value="2">OURO</option>
            </SelectInput>
            <SelectInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Tipo"
              name="tipo_pagamento"
              errors={errors.tipo_pagamento}
              value={data.tipo_pagamento}
              onChange={e => setData('tipo_pagamento', e.target.value)}
            >
              <option value=""></option>
              <option value="1">MENSAL</option>
              <option value="3">TRIMESTRAL</option>
              <option value="6">SEMESTRAL</option>
              <option value="12">ANUAL</option>
            </SelectInput>
            <SelectInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label={precopacote == undefined ? "Preço" : 'Preço: ' + currency(precopacote)}
              name="preco"
              errors={errors.preco}
              value={data.preco}
              onChange={e => setData('preco', e.target.value)}
            >
              <option>Seleccionar preço</option>
              <option value={precopacote}>{isNaN(precopacote) ? currency(0) : currency(precopacote)}</option>
            </SelectInput>
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              label="Início"
              name="inicio"
              type="date"
              errors={errors.inicio}
              value={data.inicio}
              onChange={e => getDataFinal(e)}
            />
            <TextInput
              className="w-full pb-8 pr-6 lg:w-1/2"
              id="fim"
              label={
                data.tipo_pagamento == '1'
                  ? 'Fim: ' + datafinal
                  : 'Fim: ' +
                  getDataFimTrimestralSemestralAnual(
                    data.inicio,
                    Number(data.tipo_pagamento)
                  )
              }
              name="fim"
              type="date"
              errors={errors.fim}
              value={data.fim}
              onChange={e =>
                setData(
                  'fim',
                  e.target.value == datafinal ||
                    e.target.value ==
                    getDataFimTrimestralSemestralAnual(
                      data.inicio,
                      Number(data.tipo_pagamento)
                    )
                    ? e.target.value
                    : ''
                )
              }
            />
          </div>
          <div className="flex items-center justify-star px-8 py-4 bg-gray-100 border-t border-gray-200">
            <SelectInput
              className="w-full mt-2 pb-8 pr-6 lg:w-1/2"
              label="Pagamento"
              name="pagamento"
              errors={errors.pagamento}
              value={data.pagamento}
              onChange={e => setData('pagamento', e.target.value)}
            >
              <option value=""></option>
              <option value="0">NORMAL</option>
              <option value="1">DE REGISTO</option>
            </SelectInput>
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

const ListaParceiros = (props) => {

  const { parceiros, quantidade } = usePage().props;
  const {
    data,
    meta: { links }
  } = parceiros;

  return (
    <>
      <h1 className="mb-8 text-3xl font-bold">Parceiros ({data.length} - {quantidade})</h1>
      <div className="flex items-center justify-between mb-6">
        <SearchFilter />
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
              ({ id, idcrypt, name, cantina, email, phone, estado, read_contact, deleted_at }) => (
                <tr
                  key={id}
                  className={`hover:bg-gray-100 focus-within:bg-yellow-100 ${estado == '0' ? 'bg-red-100' : 'bg-green-200'
                    }`}
                >
                  <td className="border-t">
                    <InertiaLink
                      href={route('contacts.edit', [idcrypt, 1, read_contact])}
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
                      href={route('contacts.edit', [idcrypt, 1, read_contact])}
                    >
                      {cantina}
                    </InertiaLink>
                  </td>
                  <td className="border-t">
                    <InertiaLink
                      tabIndex="-1"
                      href={route('contacts.edit', [idcrypt, 1, read_contact])}
                      className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                    >
                      {email}
                    </InertiaLink>
                  </td>
                  <td className="border-t">
                    <InertiaLink
                      tabIndex="-1"
                      href={route('contacts.edit', [idcrypt, 1, read_contact])}
                      className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                    >
                      {phone}
                    </InertiaLink>
                  </td>
                  <td className="w-px border-t">
                    <InertiaLink
                      tabIndex="-1"
                      href={route('contacts.edit', [idcrypt, 1, read_contact])}
                      className="flex items-center px-4 focus:outline-none"
                    >
                      <Icon
                        name="cheveron-right"
                        className="block w-6 h-6 text-gray-400 fill-current"
                      />
                    </InertiaLink>
                  </td>
                  <td>
                    <LoadingButton
                      onClick={e => props.getParceiro(e, id, name, cantina, phone)}
                      className={`ml-auto btn-danger`}
                    >
                      Seleccionar
                    </LoadingButton>
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
    </>
  );
}

Create.layout = page => <Layout title="Efectuar pagamento" children={page} />;

export default Create;
