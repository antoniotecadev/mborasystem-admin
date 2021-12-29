import React from 'react';
import Helmet from 'react-helmet';

const Index = () => {
  return (
    <div>
      <Helmet
        titleTemplate="%s | MboraSystem Admin"
        title="Política de Privacidade"
      />
      <div className="flex items-center justify-between w-full p-4 text-sm bg-white border-b md:py-0 md:px-12 d:text-md">
        <h1>Política de Privacidade</h1>
        <div className="mt-1 mr-4 font-bold">MboraSystem | YOGA</div>
      </div>
      <br />
      <p class="text-sm text-justify ml-4 mr-4">
        A equipa do MorbaSystem encara seriamente a privacidade e a protecção
        dos dados registados pelos seus utilizadores e assegura aos seus
        utilizadores privacidade e segurança nos dados facultados para os vários
        serviços disponibilizados, sendo apenas pedidos e recolhidos os dados
        necessários para a prestação do serviço de acordo com as indicações
        explícitas no sistema (App) e as opções do utilizador. Caso o requeira,
        o titular dos dados tem o direito de obter o acesso, rectificação ou a
        eliminação dos dados facultados.
      </p>
      <br />
      <p class="text-sm text-justify ml-4 mr-4">
        Em seguida apresentamos as práticas de tratamento de informação que
        adoptámos, incluindo a forma como a informação é recolhida, como é usada
        e com quem é partilhada.
      </p>
      <br />
      <h2 class="text-sm font-bold ml-4">
        Assumimos para com os utilizadores da app ou do site os seguintes
        compromissos:
      </h2>
      <ol class="text-sm text-justify ml-4 mr-4">
        <br />
        <li>
          1) Proceder ao tratamento de dados de forma lícita e leal, recolhendo
          apenas a informação necessária e pertinente à finalidade a que se
          destinam;
        </li>
        <br />
        <li>
          2) Permitir ao titular dos dados o acesso e correcção das informações
          sobre si registadas, transmitindo-as em linguagem clara e
          rigorosamente correspondente ao conteúdo do registo;
        </li>
        <br />
        <li>
          3) Não utilizar os dados recolhidos para finalidade incompatível com a
          da recolha;
        </li>
        <br />
        <li>4) Manter os dados exactos e, se necessário, actuais;</li>
        <br />
        <li>
          5) Assegurar o consentimento expresso do titular dos dados sempre que
          tal for exigido;
        </li>
        <br />
        <li>
          6) Garantir gratuitamente o direito de eliminação dos dados utilizados
          quando requerida pelo titular;
        </li>
        <br />
        <li>
          7) Ter sistemas de segurança que impeçam a consulta, modificação,
          destruição ou adição dos dados por pessoa não autorizada a fazê-lo e
          que permitam detectar desvios de informação intencionais ou não;
        </li>
        <br />
        <li>
          8) Respeitar o sigilo profissional em relação aos dados tratados;
        </li>
        <br />
        <li>
          9) Não realizar interconexão de dados pessoais, salvo autorização
          legal ou autorização da CNPD.
        </li>
      </ol>
      <br />
      <p class="text-sm text-justify ml-4 mr-4">
        Em geral, o sistema só pode ser acessado quando o utilizador criar uma
        conta cliente local/online. Existem, no entanto, áreas onde necessitamos
        que os utilizadores forneçam os seus dados pessoais, de forma a
        usufruírem dos serviços aí disponibilizados, podendo o utilizador optar
        por registar-se no sistema e fornecer os dados pessoais, tal como o
        nome, endereço, telefone, e-mail, etc..
      </p>
      <br />
      <p class="text-sm text-justify ml-4 mr-4">
        A recolha de dados de identificação do utilizador será efectuado através
        do preenchimento de um formulário de registo online e ocorrerá de acordo
        com as mais estritas regras de segurança, fazendo uso do protocolo de
        encriptação SSL.
      </p>
      <br />
      <p class="text-sm text-justify ml-4 mr-4">
        Os dados recolhidos pelo sistema são introduzidos no sistema informático
        que os trata, onde serão processados automaticamente, destinando-se os
        dados à gestão de serviços do sistema. Entre estes dados, estão aqueles
        referentes a entidades físicas ou jurídicas que são introduzidos no
        âmbito da emissão de documentos com eficácia fiscal.
      </p>
      <br />
      <p class="text-sm text-justify ml-4 mr-4">
        Todos os colaboradores do MboraSystem estão abrangidos por uma obrigação
        de confidencialidade relativamente aos dados aos quais tenham acesso no
        âmbito das operações da respectiva base informática, estando devidamente
        informados da importância do cumprimento desse dever legal de sigilo e
        sendo responsáveis pelo cumprimento dessa obrigação de
        confidencialidade.
      </p>
      <br />
    </div>
  );
};
export default Index;
