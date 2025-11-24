import React from "react";

export default function SearchForm({ onSearch }) {
    return (
        <div className="mb-5">
            <input
                type="text"
                placeholder="Cari nama atau NIP..."
                className="w-full md:w-1/3 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                onChange={(e) => onSearch(e.target.value)}
            />
        </div>
    );
}
