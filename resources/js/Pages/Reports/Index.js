import React from 'react';
import Layout from '@/Shared/Layout';

const Index = () => {
  return (
    <div>
      <h1 className="mb-8 text-3xl font-bold">Relatórios</h1>
    </div>
  );
};

Index.layout = page => <Layout title="Reports" children={page} />;

export default Index;
