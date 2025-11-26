import React from "react";

export default function StatCard({ title, value, icon, color = "blue", subtitle }) {
    const colorClasses = {
        blue: "bg-blue-50 text-blue-600 border-blue-200",
        green: "bg-green-50 text-green-600 border-green-200",
        yellow: "bg-yellow-50 text-yellow-600 border-yellow-200",
        red: "bg-red-50 text-red-600 border-red-200",
        purple: "bg-purple-50 text-purple-600 border-purple-200",
        orange: "bg-orange-50 text-orange-600 border-orange-200",
        gray: "bg-gray-50 text-gray-600 border-gray-200",
    };

    return (
        <div className={`rounded-lg border-2 p-4 ${colorClasses[color] || colorClasses.blue}`}>
            <div className="flex items-center justify-between">
                <div>
                    <p className="text-sm font-medium opacity-80">{title}</p>
                    <p className="text-3xl font-bold mt-1">{value}</p>
                    {subtitle && (
                        <p className="text-xs mt-1 opacity-70">{subtitle}</p>
                    )}
                </div>
                {icon && (
                    <div className="text-4xl opacity-50">
                        {icon}
                    </div>
                )}
            </div>
        </div>
    );
}
