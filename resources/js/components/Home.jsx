import axios from 'axios';
import React, { useEffect, useState } from 'react';

const Home = () => {
    const [id, setId] = useState("");
    const [notExist, setNotExist] = useState(false);
    const autoLoginUrl = `autologin?id=`;

    useEffect(() => {
        axios.get('/get-user').then((response) => {
            setId(response.data.id);
        })
    }, []);

    useEffect(() => {
        if (id == import.meta.env.VITE_USER_CALCULATOR || id == import.meta.env.VITE_USER_CALCULATOR2) {
            window.location.href = `${import.meta.env.VITE_APP_CALCULATOR_URL}${autoLoginUrl}${id}`;
        }
        else if (id == import.meta.env.VITE_USER_TRACKER || id == import.meta.env.VITE_USER_TRACKER2) {
            window.location.href = `${import.meta.env.VITE_APP_TRACKER_URL}${autoLoginUrl}${id}`;
        }
        else if (id == import.meta.env.VITE_USER_INVENTORY || id == import.meta.env.VITE_USER_INVENTORY2) {
            window.location.href = `${import.meta.env.VITE_APP_INVENTORY_URL}${autoLoginUrl}${id}`;
        }
        else if (id == import.meta.env.VITE_USER_SALES || id == import.meta.env.VITE_USER_SALES2) {
            window.location.href = `${import.meta.env.VITE_APP_SALES_URL}${autoLoginUrl}${id}`;
        }
        else if (id == import.meta.env.VITE_USER_LEDGER || id == import.meta.env.VITE_USER_LEDGER2) {
            window.location.href = `${import.meta.env.VITE_APP_LEDGER_URL}${autoLoginUrl}${id}`;
        } else {
            setNotExist(true);
        }
    }, [id])

    return (
        <div className="container px-0 pt-2 pb-0 text-center mt-5 homeHeight">
            <div className="row justify-content-center mt-4">
                <div className="col-md-4">
                    {notExist ? "User not exist" : "Redirecting..."}
                </div>
            </div>
        </div>
    );
}

export default Home;
