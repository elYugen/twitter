import Navbar from "../components/Navbar/Navbar"
import Sidebar from "../components/Sidebar/Sidebar"
import Feed from "../components/Feed/Feed"
import { FaRegCalendarAlt } from "react-icons/fa";

function Home() {
    return (
        <>
        
        <div className="layout">
            <Navbar /> 
            <div className="container">
            <div className="profileBox">
                <div className="banner">
                    <img src="https://i.pravatar.cc/300" alt="user" className="profilePicture"/>
                </div>
                <div className="userInfo">
                    <h2 className="username">username</h2>
                    <p className="email">user@user.fr</p>
                    <p className="joinDate">
                        <FaRegCalendarAlt />
                        Membre depuis le 16 Septembre
                    </p>
                </div>
                <div className="postsSection">
                    <span className="postsTitle">Publications</span>
                </div>
            </div>
            <Feed/>
            </div>
            <Sidebar />
        </div>
        
        </>
    );
}

export default Home