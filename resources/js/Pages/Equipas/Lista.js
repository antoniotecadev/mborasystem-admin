import React from 'react';
import { InertiaLink, usePage } from '@inertiajs/inertia-react';
import Helmet from 'react-helmet';
import Icon from '@/Shared/Icon';
import Pagination from '@/Shared/Pagination';
import SearchFilter from '@/Shared/SearchFilter';

const Lista = () => {
    const { equipas, quantidade } = usePage().props;
    const {
        data,
        meta: { links }
    } = equipas;
    return (
        <div>
            <Helmet title="Agentes | MboraSystem Admin" />
            <div className="flex flex-col">
                <div className="flex flex-col h-screen">
                    <div className="flex flex-grow overflow-hidden">
                        <div className="w-full px-4 py-8 overflow-hidden overflow-y-auto md:p-12">
                            <h1 className="mb-8 text-3xl font-bold">Equipas ({data.length} - {quantidade})</h1>
                            <div className="flex items-center justify-between mb-6">
                                <SearchFilter placeHolder="código yoga" />
                            </div>
                            <div className="overflow-x-auto bg-white rounded shadow">
                                <table className="w-full whitespace-nowrap">
                                    <thead>
                                        <tr className="font-bold text-left">
                                            <th className="px-6 pt-5 pb-4">Código</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {data.map(
                                            ({ id, codigo, estado, deleted_at }) => (
                                                <tr
                                                    key={id}
                                                    className={`hover:bg-gray-100 focus-within:bg-yellow-100 ${estado == '0' ? 'bg-red-100' : 'bg-green-200'
                                                        }`}
                                                >
                                                    <td className="border-t">
                                                        <InertiaLink
                                                            href={route('api.rendimento.equipas', [id, codigo])}
                                                            className="flex items-center px-6 py-4 focus:text-indigo-700 focus:outline-none"
                                                        >
                                                            {estado == '0' ? '🔴' : '🟢'} YOGA {codigo}
                                                            {deleted_at && (
                                                                <Icon
                                                                    name="trash"
                                                                    className="flex-shrink-0 w-3 h-3 ml-2 text-gray-400 fill-current"
                                                                />
                                                            )}
                                                        </InertiaLink>
                                                    </td>
                                                </tr>
                                            )
                                        )}
                                        {data.length === 0 && (
                                            <tr>
                                                <td className="px-6 py-4 border-t" colSpan="4">
                                                    Nenhuma equipa encontrada.
                                                </td>
                                            </tr>
                                        )}
                                    </tbody>
                                </table>
                            </div>
                            <Pagination links={links} />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};
export default Lista;


