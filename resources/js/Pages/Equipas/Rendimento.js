import React, { useState } from 'react';
import Helmet from 'react-helmet';
import { Inertia } from '@inertiajs/inertia';
import { InertiaLink, usePage, useForm } from '@inertiajs/inertia-react';
import DeleteButton from '@/Shared/DeleteButton';
import LoadingButton from '@/Shared/LoadingButton';
import TextInput from '@/Shared/TextInput';
import TrashedMessage from '@/Shared/TrashedMessage';
import Icon from '@/Shared/Icon';
import { currency } from '@/Util/utilitario';
import { isUndefined } from 'lodash';
// import Pagination from '@/Shared/Pagination';

const Rendimento = () => {
    const [senha, setSenha] = useState(false);
    const { equipa, parceiros, valorcada, valortotal, valortotalbruto, iniciodata, fimdata, numeroagente, percentagemtaxa, quantidade } = usePage().props;
    const [inicio, setInicio] = useState(iniciodata);
    const [fim, setFim] = useState(fimdata);
    const [numeroAgente, setNumeroAgente] = useState(2);
    const [percentagemTaxa, setPercentagemTaxa] = useState(30);
    const { data, setData, errors, put, processing } = useForm({
        codigo: equipa.codigo || '',
        estado: equipa.estado || '',
        password: '',
        created_at: equipa.created_at || ''
    });
    // const {
    //     dadosParceiros,
    //     meta: { links }
    //   } = parceiros;

    function handleSubmitPassword(e) {
        e.preventDefault();
        if (equipa.deleted_at) {
            alert("⚠ Password de Equipa eliminada não pode ser actualizada.");
        } else {
            put(route('password.update', equipa.id));
        }
    }

    function calcularRendimento(e) {
        e.preventDefault();
        if (isUndefined(inicio)) {
            alert('Data de início não definada');
        } else if (isUndefined(fim)) {
            alert('Data de fim não definada');
        } else {
            Inertia.get(route('api.calcular.rendimento.equipa', [equipa.id, equipa.codigo, inicio, fim, numeroAgente, percentagemTaxa]));
        }
    }

    const pct = ['BRONZE', 'ALUMÍNIO', 'OURO']

    return (
        <div>
            <div className="flex flex-col">
                <div className="flex flex-col h-screen">
                    <div className="flex flex-grow overflow-hidden">
                        <div className="w-full px-4 py-8 overflow-hidden overflow-y-auto md:p-12">
                            <div>
                                <Helmet title={`${data.codigo} ${' | MboraSystem Admin'}`} />
                                <h1 className="mb-8 text-3xl font-bold">
                                    <InertiaLink
                                        href={route('api.lista.equipas')}
                                        className="text-indigo-600 hover:text-indigo-700"
                                    >
                                        Equipas
                                    </InertiaLink>
                                    <span className="mx-2 font-medium text-indigo-600">/</span>
                                    <span
                                        className={`${data.estado == '0' ? 'text-red-400' : 'text-green-400'
                                            }`}
                                    >
                                        {data.estado == '0' ? 'Desactivo' : 'Activo'}
                                    </span>
                                </h1>
                                {equipa.deleted_at && (
                                    <TrashedMessage>
                                        <p>Esta equipa foi eliminada.{' '}<DeleteButton onDelete={e => alert(equipa.motivo_elimina)}>Motivo</DeleteButton></p>
                                    </TrashedMessage>
                                )}
                                <h2 className="mt-12 text-2xl font-bold">Agentes</h2>
                                <div className="mt-6 overflow-x-auto bg-white rounded shadow">
                                    <table className="w-full whitespace-nowrap">
                                        <thead>
                                            <tr className="font-bold text-left">
                                                <th className="px-6 pt-5 pb-4">Nome completo</th>
                                                <th className="px-6 pt-5 pb-4">Telefone</th>
                                                <th className="px-6 pt-5 pb-4">Email</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {equipa.agentes.map(
                                                ({ id, nome_completo, telefone, email, estado, deleted_at }) => {
                                                    return (
                                                        <tr
                                                            key={id}
                                                            className={`hover:bg-gray-100 focus-within:bg-gray-100 ${estado == '0' ? 'bg-red-100' : 'bg-green-200'
                                                                }`}
                                                        >
                                                            <td className="border-t">
                                                                <p
                                                                    className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                                                                >
                                                                    {nome_completo}
                                                                    {deleted_at && (
                                                                        <Icon
                                                                            name="trash"
                                                                            className="flex-shrink-0 w-3 h-3 ml-2 text-gray-400 fill-current"
                                                                        />
                                                                    )}
                                                                </p>
                                                            </td>
                                                            <td className="border-t">
                                                                <p
                                                                    tabIndex="-1"
                                                                    className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                                                                >
                                                                    {telefone}
                                                                </p>
                                                            </td>
                                                            <td className="border-t">
                                                                <p
                                                                    tabIndex="-1"
                                                                    className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                                                                >
                                                                    {email}
                                                                </p>
                                                            </td>
                                                            <td className="w-px border-t">
                                                                <p
                                                                    tabIndex="-1"
                                                                    href={route('agentes.edit', id)}
                                                                    className="flex items-center px-4"
                                                                >
                                                                    <Icon
                                                                        name="cheveron-right"
                                                                        className="block w-6 h-6 text-gray-400 fill-current"
                                                                    />
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    );
                                                }
                                            )}
                                            {equipa.agentes.length === 0 && (
                                                <tr>
                                                    <td className="px-6 py-4 border-t" colSpan="4">
                                                        Não foram encontrados agentes.
                                                    </td>
                                                </tr>
                                            )}
                                        </tbody>
                                    </table>
                                </div>
                                <h2 className="mt-12 text-2xl font-bold">Rendimento ({iniciodata == undefined ? "" : iniciodata + ' - ' + fimdata == undefined ? "" : fimdata})</h2>
                                <div className="mt-6 max-w-3xl overflow-hidden bg-white rounded shadow">
                                    <form onSubmit={calcularRendimento}>
                                        <div className="flex flex-wrap p-8 -mb-8 -mr-6">
                                            <TextInput
                                                className="w-full pb-8 pr-6 lg:w-1/2"
                                                label={iniciodata == undefined ? "De" : "De: " + iniciodata}
                                                name="inicio"
                                                type="date"
                                                value={inicio}
                                                onChange={e => setInicio(e.target.value)}
                                            />
                                            <TextInput
                                                className="w-full pb-8 pr-6 lg:w-1/2"
                                                label={fimdata == undefined ? "Até" : "Até: " + fimdata}
                                                name="fim"
                                                type="date"
                                                value={fim}
                                                onChange={e => setFim(e.target.value)}
                                            />
                                            <TextInput
                                                className="w-full pb-8 pr-6 lg:w-1/2"
                                                label={numeroagente == undefined ? "Para" : "Para: " + numeroagente + " Agente(s)"}
                                                name="numero_agente"
                                                type="number"
                                                value={numeroAgente}
                                                onChange={e => setNumeroAgente(e.target.value)}
                                                min={1}
                                                max={4}
                                            />
                                            <TextInput
                                                className="w-full pb-8 pr-6 lg:w-1/2"
                                                label={percentagemtaxa == undefined ? "Percentagem" : "Percentagem: " + percentagemtaxa + " %"}
                                                name="percentagem"
                                                type="number"
                                                value={percentagemTaxa}
                                                onChange={e => setPercentagemTaxa(e.target.value)}
                                                min={1}
                                                max={100}
                                            />
                                        </div>
                                        <div className="flex items-center justify-end px-8 py-4 bg-gray-100 border-t border-gray-200">
                                            <LoadingButton
                                                className="btn-indigo"
                                                loading={processing}
                                                type="submit"
                                            >
                                                Calcular
                                            </LoadingButton>
                                        </div>
                                    </form>
                                </div>
                                <p></p>
                                <div className="mt-6 overflow-x-auto bg-white rounded shadow">
                                    <table className="w-full whitespace-nowrap">
                                        <thead>
                                            <tr className="font-bold text-left">
                                                <th className="px-6 pt-5 pb-4">Percentagem</th>
                                                <th className="px-6 pt-5 pb-4">Valor (Para cada)</th>
                                                <th className="px-6 pt-5 pb-4">Valor total</th>
                                                <th className="px-6 pt-5 pb-4">Valor total (Bruto)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                key="1"
                                                className="hover:bg-gray-100 focus-within:bg-gray-100 bg-green-200"
                                            >
                                                <td className="border-t">
                                                    <InertiaLink className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none">
                                                        {percentagemTaxa}%
                                                    </InertiaLink>
                                                </td>
                                                <td className="border-t">
                                                    <InertiaLink
                                                        tabIndex="-1"
                                                        className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                                                    >
                                                        {valorcada && currency(valorcada)}
                                                    </InertiaLink>
                                                </td>
                                                <td className="border-t">
                                                    <InertiaLink
                                                        tabIndex="-1"
                                                        className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                                                    >
                                                        {valortotal && currency(valortotal)}
                                                    </InertiaLink>
                                                </td>
                                                <td className="w-px border-t">
                                                    <InertiaLink tabIndex="-1" className="flex items-center px-4">
                                                        {valortotalbruto && currency(valortotalbruto)}
                                                    </InertiaLink>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <h2 className="mt-12 text-2xl font-bold">Parceiros ({quantidade})</h2>
                                <div className="mt-6 overflow-x-auto bg-white rounded shadow">
                                    <table className="w-full whitespace-nowrap">
                                        <thead>
                                            <tr className="font-bold text-left">
                                                <th className="px-6 pt-5 pb-4">Parceiro</th>
                                                <th className="px-6 pt-5 pb-4">IMEI</th>
                                                <th className="px-6 pt-5 pb-4">Data (Parceiro)</th>
                                                <th className="px-6 pt-5 pb-4">Pacote</th>
                                                <th className="px-6 pt-5 pb-4">Preço</th>
                                                <th className="px-6 pt-5 pb-4">Data (Pagamento)</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            {parceiros && parceiros.map(
                                                ({ idcontact, first_name, last_name, imei, read_contact, datacriacontact, pacote, preco, datacriapagamento }) => {
                                                    return (
                                                        <tr
                                                            key={idcontact}
                                                            className='hover:bg-gray-100 focus-within:bg-gray-100 bg-yellow-200'>
                                                            <td className="border-t">
                                                                <p
                                                                    className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                                                                >
                                                                    {first_name + " " + last_name}
                                                                </p>
                                                            </td>
                                                            <td className="border-t">
                                                                <p
                                                                    tabIndex="-1"
                                                                    className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                                                                >
                                                                    {imei}
                                                                </p>
                                                            </td>
                                                            <td className="border-t">
                                                                <p
                                                                    tabIndex="-1"
                                                                    className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                                                                >
                                                                    {datacriacontact}
                                                                </p>
                                                            </td>
                                                            <td className="w-px border-t">
                                                                <p
                                                                    tabIndex="-1"
                                                                    className="flex items-center px-4"
                                                                >
                                                                    {pct[pacote]}
                                                                </p>
                                                            </td>
                                                            <td className="border-t">
                                                                <p
                                                                    tabIndex="-1"
                                                                    className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                                                                >
                                                                    {currency(preco)}
                                                                </p>
                                                            </td>
                                                            <td className="border-t">
                                                                <p
                                                                    tabIndex="-1"
                                                                    className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                                                                >
                                                                    {datacriapagamento}
                                                                    <Icon
                                                                        name="cheveron-right"
                                                                        className="block w-6 h-6 text-gray-400 fill-current"
                                                                    />
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    );
                                                }
                                            )}
                                            {parceiros && parceiros.length === 0 && (
                                                <tr>
                                                    <td className="px-6 py-4 border-t" colSpan="4">
                                                        Não foram encontrados parceiros.
                                                    </td>
                                                </tr>
                                            )}
                                        </tbody>
                                    </table>
                                </div>
                                {/* <Pagination links={links} /> */}
                                <div className="max-w-3xl mt-8 overflow-hidden bg-white rounded shadow">
                                    <form onSubmit={handleSubmitPassword}>
                                        <div className="flex flex-wrap p-8 -mb-8 -mr-6">
                                            <TextInput
                                                className="w-full pb-8 pr-6 lg:w-1/2"
                                                label="Palavra passe"
                                                name="password"
                                                type={`${senha ? 'text' : 'password'}`}
                                                errors={errors.password}
                                                value={data.password}
                                                onChange={e => setData('password', e.target.value)}
                                            />
                                            <div className="flex items-center justify-end mb-2">
                                                <div className="w-full pb-4 pr-6 mt-4">
                                                    <label htmlFor="password" className="mr-4">
                                                        {senha ? 'Ocultar' : 'Visualizar'}
                                                    </label>
                                                    <input
                                                        type="checkbox"
                                                        id="password"
                                                        onChange={e => setSenha(!senha)}
                                                    />
                                                </div>
                                            </div>
                                            <div className="flex items-center justify-end mb-2">
                                                <LoadingButton
                                                    loading={processing}
                                                    type="submit"
                                                    className="ml-auto btn-indigo"
                                                >
                                                    Alterar palavra passe
                                                </LoadingButton>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Rendimento;
