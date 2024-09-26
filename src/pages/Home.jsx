import Navbar from "../components/Navbar/Navbar";
import Sidebar from "../components/Sidebar/Sidebar";
import Feed from "../components/Feed/Feed";
import CreatePost from "../components/CreatePost/CreatePost";
import axios from "axios";
import React, { useEffect, useState } from 'react';

function Home() {
    const [session, setSession] = useState(null);

    useEffect(() => {

        // session utilisateur
        axios.get("http://localhost/twitter/backend/controller/Session.php", { withCredentials: true })
        .then(response => {
            setSession(response.data);
        })
        .catch(error => {
            console.log("Erreur lors de la récupération de la session:", error);
        });

    }, []);

    return (
        <>
            <div className="layout">
                <Navbar /> 
                <div className="container">
                    {session && (
                        <CreatePost />
                    )}
                    <Feed />
                </div>
                <Sidebar />
            </div>
        </>
    );
}

export default Home;
