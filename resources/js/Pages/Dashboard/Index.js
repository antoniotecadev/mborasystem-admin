import React, { PureComponent } from 'react';
import { InertiaLink } from '@inertiajs/inertia-react';
import Layout from '@/Shared/Layout';
import { PieChart, Pie, Sector, Cell, ResponsiveContainer } from 'recharts';

const data = [
  { name: 'Group A', value: 400 },
  { name: 'Group B', value: 300 },
];

const COLORS = ['#00C49F', '#ed5c5c'];

const RADIAN = Math.PI / 180;
const renderCustomizedLabel = ({ cx, cy, midAngle, innerRadius, outerRadius, percent, index }) => {
  const radius = innerRadius + (outerRadius - innerRadius) * 0.5;
  const x = cx + radius * Math.cos(-midAngle * RADIAN);
  const y = cy + radius * Math.sin(-midAngle * RADIAN);

  return (
    <text x={x} y={y} fill="white" textAnchor={x > cx ? 'start' : 'end'} dominantBaseline="central">
      {`${(percent * 100).toFixed(0)}%`}
    </text>
  );
};

const Dashboard = () => {
  return (
    <div>
      <h1 className="mb-4 text-3xl font-bold">Dashboard</h1>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-2">
        <CardHeader grafico={<GraficoQuantidadeParceiro />} referente="PARCEIROS" quantidade="200.000" activos="150.000" desactivos="50.000" />
        <CardHeader grafico={<GraficoQuantidadeParceiro />} referente="EQUIPAS" quantidade="100" activos="40" desactivos="60"/>
        <CardHeader grafico={<GraficoQuantidadeParceiro />} referente="PAGAMENTOS" quantidade=" 130" activos="60" desactivos="70"/>
      </div>
      <p className="mb-12 leading-normal">
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
      </p>
      <div>
        <InertiaLink className="mr-1 btn-indigo" href="/500">
          500 error
        </InertiaLink>
        <InertiaLink className="btn-indigo" href="/404">
          404 error
        </InertiaLink>
      </div>
    </div>
  );
};

// Persistent layout
// Docs: https://inertiajs.com/pages#persistent-layouts
Dashboard.layout = page => <Layout title="Dashboard" children={page} />;

export default Dashboard;

const CardHeader = ({ grafico, referente, quantidade, activos, desactivos }) => {
  return (
    <div className="justify-center py-8 px-8 max-w bg-white rounded-xl shadow-lg space-y-2 sm:py-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-6">
      <div className="text-center space-y-2 sm:text-left">
        <div className="space-y-0.5">
          <p className="text-md text-black font-bold">
            {referente}
          </p>
          <p className="text-md font-light">
            {quantidade}
          </p>
          <p className="text-xs font-medium">
            <span style={{ color: '#00C49F' }}>●</span> {activos} activos
          </p>
          <p className="text-xs font-medium">
            <span style={{ color: '#0088FE' }}>●</span> {desactivos} desactivos
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

  render() {
    return (
      <PieChart width={100} height={100} onMouseEnter={this.onPieEnter}>
        <Pie
          data={data}
          label={renderCustomizedLabel}
          outerRadius={50}
          fill="#8884d8"
          paddingAngle={5}
          dataKey="value"
        >
          {data.map((entry, index) => (
            <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />
          ))}
        </Pie>
      </PieChart>
    );
  }
}
