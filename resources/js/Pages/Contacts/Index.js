import React from 'react';
import { InertiaLink, usePage } from '@inertiajs/inertia-react';
import Layout from '@/Shared/Layout';
import Icon from '@/Shared/Icon';
import Pagination from '@/Shared/Pagination';
import SearchFilter from '@/Shared/SearchFilter';
import CryptoJS from 'crypto-js';

const Index = () => {

  var CryptoJSAesJson = {
    stringify: function (cipherParams) {
        var j = {ct: cipherParams.ciphertext.toString(CryptoJS.enc.Base64)};
        if (cipherParams.iv) j.iv = cipherParams.iv.toString();
        if (cipherParams.salt) j.s = cipherParams.salt.toString();
        return JSON.stringify(j);
    },
    parse: function (jsonStr) {
        var j = JSON.parse(jsonStr);
        var cipherParams = CryptoJS.lib.CipherParams.create({ciphertext: CryptoJS.enc.Base64.parse(j.ct)});
        if (j.iv) cipherParams.iv = CryptoJS.enc.Hex.parse(j.iv)
        if (j.s) cipherParams.salt = CryptoJS.enc.Hex.parse(j.s)
        return cipherParams;
    }
}

  const { contacts } = usePage().props;
  const {
    data,
    meta: { links }
  } = contacts;
  return (
    <div>
      <h1 className="mb-8 text-3xl font-bold">Parceiros ({data.length})</h1>
      <div className="flex items-center justify-between mb-6">
        <SearchFilter />
        <InertiaLink
          className="btn-indigo focus:outline-none"
          href={route('contacts.create')}
        >
          <span>Criar</span>
          <span className="hidden md:inline"> Parceiros</span>
        </InertiaLink>
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
            </tr>
          </thead>
          <tbody>
            {data.map(({ id, name, cantina, email, phone, estado, deleted_at }) => (
              <tr
                key={id}
                className={`hover:bg-gray-100 focus-within:bg-yellow-100 ${estado == '0'?'bg-red-100':'bg-green-200'}`}
              >
                <td className="border-t">
                  <InertiaLink
                    href={route('contacts.edit', id)}
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
                    href={route('contacts.edit', id)}
                  >
                    {/* {organization ? organization.name : ''} */}
                    {cantina}
                  </InertiaLink>
                </td>
                <td className="border-t">
                  <InertiaLink
                    tabIndex="-1"
                    href={route('contacts.edit', id)}
                    className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                  >
                    {email}
                  </InertiaLink>
                </td>
                <td className="border-t">
                  <InertiaLink
                    tabIndex="-1"
                    href={route('contacts.edit', id)}
                    className="flex items-center px-6 py-4 focus:text-indigo focus:outline-none"
                  >
                    {phone}
                  </InertiaLink>
                </td>
                <td className="w-px border-t">
                  <InertiaLink
                    tabIndex="-1"
                    href={route('contacts.edit', id)}
                    className="flex items-center px-4 focus:outline-none"
                  >
                    <Icon
                      name="cheveron-right"
                      className="block w-6 h-6 text-gray-400 fill-current"
                    />
                  </InertiaLink>
                </td>
              </tr>
            ))}
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
    </div>
  );
};

Index.layout = page => <Layout title="Contacts" children={page} />;

export default Index;
