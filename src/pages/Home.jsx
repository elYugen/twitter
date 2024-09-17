import Navbar from "../components/Navbar/Navbar";
import Sidebar from "../components/Sidebar/Sidebar";
import Feed from "../components/Feed/Feed";
import CreatePost from "../components/CreatePost/CreatePost";
import axios from "axios";
import React, { useEffect, useState } from 'react';

function Home() {
    const [data, setData] = useState([]);
    const [session, setSession] = useState(null);

    useEffect(() => {
        // publication
        axios.get("http://localhost/twitter/backend/getAllPosts.php")
        .then(response => {
            setData(response.data);
        })
        .catch(error => {
            console.log(error);
        });

        // session utilisateur
        axios.get("http://localhost/twitter/backend/session.php", { withCredentials: true })
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
                    <CreatePost />
                    {session && (
                        <div className="session-info">
                            <p>Utilisateur : {session.username}</p>
                            <p>Email : {session.email}</p>
                            <img src={session.pictures} alt="Profil utilisateur" />
                        </div>
                    )}
                    <Feed data={data} />
                </div>
                <Sidebar />
            </div>
        </>
    );
}

export default Home;
