import { Button } from "@/Components/ui/button";
import {
    Form,
    FormControl,
    FormDescription,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/Components/ui/form";
import { Input } from "@/Components/ui/input";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { Head, useForm } from "@inertiajs/react";

export default function CreateUser() {
    const { data, setData, post, processing, errors } = useForm({
        email: "",
        name: "",
    });

    const onSubmit = (e: any) => {
        e.preventDefault();
        post(route("users.store"));
    };

    return (
        <>
            <DashboardLayout>
                <div className="flex flex-col gap-4">
                    <Head title="Create User" />
                    <div className="flex items-center justify-between">
                        <h1 className="text-lg font-semibold md:text-2xl">
                            Create User
                        </h1>
                    </div>

                    <div className="mt-4 flex flex-col gap-4">
                        <form onSubmit={onSubmit} className="space-y-8">
                            <div>
                                <Input
                                    type="text"
                                    name="name"
                                    placeholder="Name"
                                    value={data.name}
                                    onChange={(e) =>
                                        setData("name", e.target.value)
                                    }
                                />
                                {errors.name && <div>{errors.name}</div>}
                            </div>
                            <div>
                                <Input
                                    type="email"
                                    name="email"
                                    placeholder="Email"
                                    value={data.email}
                                    onChange={(e) =>
                                        setData("email", e.target.value)
                                    }
                                />
                                {errors.email && <div>{errors.email}</div>}
                            </div>
                            <Button type="submit">Submit</Button>
                        </form>
                    </div>
                </div>
            </DashboardLayout>
        </>
    );
}
