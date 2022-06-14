import React, { useState, useEffect } from 'react';
import Layout from '@/Shared/Layout';
import LoadingButton from '@/Shared/LoadingButton';
import firebase from '@/firebase';
import { ref, onChildAdded, query } from "firebase/database";

const Index = () => {

    const [contact, setContact] = useState([]);

    const handleSubmit = (e, type) => {

    }

    useEffect(() => {
        const cliente = query(ref(firebase, 'cliente'));
        /* OUVIR EVENTOS DO REALTIME DATABASE */
        onChildAdded(cliente, (snapshot) => {
            setContact((contacts) => [...contacts, JSON.stringify(snapshot.val()) +"\n\n"]);
        });
        return () => {
            cliente.off();
        };
    }, []);

    return (
        <div>
            <h1 className="mb-2 text-3xl font-bold">Registos do firebase</h1>
            <textarea value={contact} onChange={() => setContact(contact)} disabled className="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none" rows="15" />
        </div>
    );
};

Index.layout = page => <Layout title="Registos do firebase" children={page} />;

export default Index;
