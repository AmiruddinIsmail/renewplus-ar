import { Toaster } from "@/components/ui/toaster";
import { PropsWithChildren } from "react";

export default function Guest({ children }: PropsWithChildren) {
    return (
        <div className="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0">
            {children}

            <Toaster />
        </div>
    );
}
