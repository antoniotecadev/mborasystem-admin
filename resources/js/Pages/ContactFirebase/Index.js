import React, { useState, useEffect } from 'react';
import Layout from '@/Shared/Layout';
import LoadingButton from '@/Shared/LoadingButton';
import { initializeApp } from "firebase/app";
import { getDatabase, ref, onChildAdded, query, limitToLast } from "firebase/database";

const Index = () => {

    const [contact, setContact] = useState([]);

    const firebaseConfig = {
        apiKey: "AIzaSyCRevSFiqHU5TfNNgGHc2bgAPi9MseAxaM",
        authDomain: "mborasystem-admin.firebaseapp.com",
        databaseURL: "https://mborasystem-admin-default-rtdb.firebaseio.com",
        projectId: "mborasystem-admin",
        storageBucket: "mborasystem-admin.appspot.com",
        messagingSenderId: "1024278380960",
        appId: "1:1024278380960:web:6ad069d4010a69662c24c5",
        measurementId: "G-ZW05HBY5YG"
    };

    const handleSubmit = (e, type) => {

    }

    useEffect(() => {

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        // Get a reference to the database service
        const database = getDatabase(app);

        const cliente = query(ref(database, 'cliente'));

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
