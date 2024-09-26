import axios from 'axios';
import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom'; 

function Login() {
    const [username, setUsername] = useState('');
    const [password, setPassword] = useState('');
    const [email, setEmail] = useState('');
    const [error, setError] = useState('');
    const navigate = useNavigate(); 

    const handleSubmit = async (event) => {
        event.preventDefault();
        setError('');

        try {
            const response = await axios.post('http://localhost/twitter/backend/controller/UserRegister.php', {
                register: true,
                username,
                password,
                email
            }, {
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            console.log("Réponse :", response.data);  
            
            if (response.data.success) {
                console.log("Succès :", response.data.message);
                navigate('/login');
            } else {
                setError(response.data.error || "une erreur est survenue");
            }
        } catch (error) {
            console.error("Erreur Axios :", error);
            setError(error.response?.data?.error || "une erreur est survenue lors de la connexion au serveur");
        }
    };

    return (
        <div className="loginbody">
            <div className="wrapper">
                <form onSubmit={handleSubmit}>
                    <h1>Inscription</h1>
                    {error && <div className="error-message">{error}</div>}
                    <div className="input-box">
                        <input type="text" placeholder="Pseudo" value={username} onChange={(e) => setUsername(e.target.value)} required/>
                    </div>
                    <div className="input-box">
                        <input type="email" placeholder="Adresse mail" value={email} onChange={(e) => setEmail(e.target.value)} required/>
                    </div>
                    <div className="input-box">
                        <input type="password" placeholder="Mot de passe" value={password} onChange={(e) => setPassword(e.target.value)} required/>
                    </div>
                    <button type="submit" className="btn">Inscription</button>
                    <p><a href="/login">Déjà un compte ? Se connecter</a></p>
                </form>
            </div>
        </div>
    );
}

export default Login;