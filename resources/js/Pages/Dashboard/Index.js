import React, { PureComponent } from 'react';
import { InertiaLink, usePage } from '@inertiajs/inertia-react';
import Layout from '@/Shared/Layout';
import { PieChart, Pie, Cell, BarChart, Bar, XAxis, YAxis, CartesianGrid, ResponsiveContainer, Tooltip, Legend } from 'recharts';

const dados = (data) => {
  return [
    { name: 'Group A', value: Number(data.activos) },
    { name: 'Group B', value: Number(data.desactivos) },
    { name: 'Group C', value: Number(data.eliminados) },
  ];
}

const COLORS_1 = ['#00C49F', '#FFBB28', '#ed5c5c'];
const COLORS_2 = ['#0088FE', '#00C49F', '#0F0B28', '#FF8042', '#007879', '#2BB512', '#BB1478', '#00C49F', '#BBFF28'];


const RADIAN = Math.PI / 180;
const renderCustomizedLabel = ({ cx, cy, midAngle, innerRadius, outerRadius, percent, index }) => {
  const radius = innerRadius + (outerRadius - innerRadius) * 0.5;
  const x = cx + radius * Math.cos(-midAngle * RADIAN);
  const y = cy + radius * Math.sin(-midAngle * RADIAN);

  return (
    <text x={x} y={y} style={{ fontSize: '12' }} fill="white" textAnchor={x > cx ? 'start' : 'end'} dominantBaseline="central">
      {`${(percent * 100).toFixed(0)}%`}
    </text>
  );
};

const Dashboard = () => {
  const {
    parceiro,
    equipa,
    pagamento,
    municipios_parceiros_activos,
    municipios_parceiros_desactivos,
  } = usePage().props;
  return (
    <div>
      <h1 className="mb-4 text-3xl font-bold">Dashboard</h1>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-2">
        <CardHeader grafico={<GraficoQuantidadeParceiro data={dados(parceiro)} />} referente="PARCEIROS" data={parceiro} />
        <CardHeader grafico={<GraficoQuantidadeParceiro data={dados(equipa)} />} referente="EQUIPAS" data={equipa} />
        <CardHeader grafico={<GraficoQuantidadeParceiro data={dados(pagamento)} />} referente="PAGAMENTOS" data={pagamento} />
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-2 mt-2">
        <GrafQuantParcMuni municipios_parceiros={municipios_parceiros_activos} estado="PARCEIROS ACTIVOS" />
        <GrafQuantParcMuni municipios_parceiros={municipios_parceiros_desactivos} estado="PARCEIROS DESACTIVOS" />
      </div>
      {/* <p className="mb-12 leading-normal">
        Hey there! Welcome to Ping CRM, a demo app designed to help illustrate
        how
        <a
          className="mx-1 text-indigo-600 underline hover:text-orange-500"
          href="https://inertiajs.com"
        >
          Inertia.js
        </a>
        works with
        <a
          className="ml-1 text-indigo-600 underline hover:text-orange-500"
          href="https://reactjs.org/"
        >
          React
        </a>
        .
      </p> */}
      {/* <div>
        <InertiaLink className="mr-1 btn-indigo" href="/500">
          500 error
        </InertiaLink>
        <InertiaLink className="btn-indigo" href="/404">
          404 error
        </InertiaLink>
      </div> */}
    </div>
  );
};

// Persistent layout
// Docs: https://inertiajs.com/pages#persistent-layouts
Dashboard.layout = page => <Layout title="Dashboard" children={page} />;

export default Dashboard;

const CardHeader = ({ grafico, referente, data }) => {
  return (
    <div className="justify-center py-8 px-8 max-w bg-white rounded-xl shadow-lg space-y-2 sm:py-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-6">
      <div className="text-center space-y-2 sm:text-left">
        <div className="space-y-0.5">
          <p className="text-md text-black font-bold">
            {referente}
          </p>
          <p className="text-md font-light">
            {data.total}
          </p>
          <p className="text-xs font-medium">
            <span style={{ color: '#00C49F' }}>●</span> {data.activos} activos
          </p>
          <p className="text-xs font-medium">
            <span style={{ color: '#FFBB28' }}>●</span> {data.desactivos} desactivos
          </p>
          <p className="text-xs font-medium">
            <span style={{ color: '#ed5c5c' }}>●</span> {data.eliminados} Eliminados
          </p>
        </div>
      </div>
      <div className="flex justify-center">
        {grafico}
      </div>
    </div>);
}

class GraficoQuantidadeParceiro extends PureComponent {
  static demoUrl = 'https://codesandbox.io/s/pie-chart-with-padding-angle-7ux0o';

  constructor(props) {
    super(props);
  }

  render() {
    return (
      <PieChart width={100} height={100} onMouseEnter={this.onPieEnter}>
        <Pie
          data={this.props.data}
          cx="50%"
          cy="50%"
          labelLine={false}
          label={renderCustomizedLabel}
          outerRadius={50}
          fill="#8884d8"
          paddingAngle={1}
          dataKey="value"
        >
          {this.props.data.map((entry, index) => (
            <Cell key={`cell-${index}`} fill={COLORS_1[index % COLORS_1.length]} />
          ))}
        </Pie>
      </PieChart>
    );
  }
}


function GrafQuantParcMuni({ municipios_parceiros, estado }) {
  let i = 0;
  const data = []
  municipios_parceiros.map(({ numero_activo }) => {
    data.push({
      activos: numero_activo,
    });
  });
  return (
    <div className="justify-center py-8 max-w bg-white rounded-xl shadow-lg space-y-2 sm:py-4 sm:flex sm:items-center sm:space-y-0">
      <div className="sm: text-center space-y-2">
        <p className="text-md text-black font-bold">
          {estado}
        </p>
        {municipios_parceiros.map(({ id, municipality, numero_activo }) => (
          <p className="text-xs font-medium" key={id}>
            <span style={{ color: COLORS_2[i++] }}>●</span> {municipality} - {(Number(numero_activo))}
          </p>
        ))}
      </div>
      <BarChart
        width={300}
        height={200}
        data={data}
        margin={{
          top: 20,
          right: 30,
          left: 20,
          bottom: 2
        }}
      >
        <CartesianGrid strokeDasharray="3 3" />
        <XAxis dataKey="name" />
        <YAxis />
        <Tooltip />
        <Bar
          dataKey="activos"
          fill="#8884d8"
          label={{ fontSize: "12" }}>
          {data.map((entry, index) => (
            <Cell key={`cell-${index}`} fill={COLORS_2[index % 20]} />
          ))}
        </Bar>
      </BarChart>
    </div>
  );
}
