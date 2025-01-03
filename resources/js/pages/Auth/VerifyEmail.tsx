import { Button } from "@/components/ui/button";
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/components/ui/card";
import { Separator } from "@/components/ui/separator";
import GuestLayout from "@/layouts/guest-layout";
import { Head, Link, useForm } from "@inertiajs/react";
import { FormEventHandler } from "react";

export default function VerifyEmail({ status }: { status?: string }) {
    const { post, processing } = useForm({});

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route("verification.send"));
    };

    return (
        <GuestLayout>
            <Head title="Email Verification" />

            <Card className="mx-auto max-w-sm">
                <CardHeader>
                    <CardTitle className="text-2xl text-center">
                        Verify Who You Are
                    </CardTitle>
                    <CardDescription>
                        Thanks for signing up! Before getting started, could you
                        verify your email address by clicking on the link we
                        just emailed to you? If you didn't receive the email, we
                        will gladly send you another.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form onSubmit={submit} className="grid gap-4">
                        <Button
                            disabled={processing}
                            type="submit"
                            className="w-full"
                        >
                            Resend Verification Email
                        </Button>
                    </form>
                    <Separator className="mt-4 mb-2" />
                    <div className="flex justify-end">
                        <Link
                            href={route("logout")}
                            method="post"
                            as="button"
                            className="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Log Out
                        </Link>
                    </div>
                    {status === "verification-link-sent" && (
                        <div className="mb-4 text-sm font-medium text-green-600">
                            A new verification link has been sent to the email
                            address you provided during registration.
                        </div>
                    )}
                </CardContent>
            </Card>
        </GuestLayout>
    );
}
