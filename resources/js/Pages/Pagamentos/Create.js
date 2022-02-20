import React, { useState } from 'react';
import { InertiaLink, useForm, usePage } from '@inertiajs/inertia-react';
import Layout from '@/Shared/Layout';
import LoadingButton from '@/Shared/LoadingButton';
import TextInput from '@/Shared/TextInput';
import SelectInput from '@/Shared/SelectInput';
import { getDataFimTrimestralSemestralAnual, tipoPacote } from '@/Util/utilitario';

const Create = () => {
  const [datafinal, setDataFinal] = useState();
  const { contacts } = usePage().props;
  const { data, setData, errors, post, processing } = useForm({
    pacote: '',
    tipo_pagamento: '',
    preco: '',
    inicio: '',
    fim: '',
    nome: '',
    contact_id: ''
  });

  function diasNoMes(mes, ano) {
    var data = new Date(ano, mes, 0);
    return parseInt(data.getDate());
  }

  const getDataFinal = (e) => {
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
  }

  function handleSubmit(e) {
    e.preventDefault();
    post(route('pagamentos.store'));
  }

    const precopacote = tipoPacote(Number(data.pacote), Number(data.tipo_pagamento));

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
              onChange={e => setData('contact_id', e.target.value)}
            >
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
              label={"Preço: " + precopacote }
              name="preco"
              type="text"
              errors={errors.preco}
              value={data.preco}
              onChange={e => setData('preco', e.target.value)}
            >
              <option>Seleccionar preço</option>
              <option value={precopacote}>{precopacote}</option>
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
